<?php
  /**
   * Contains functions for performing commands on the DB.
   */
  class DBExecute {
    /**
     * Inserts the document $data into the database. $data must be nonempty.
     * Returns the document _id, or null if error.
     */
    public static function insert($collection, $data) {
      if (count($data) == 0) {
        return null;
      }

      $collection->insert($data);
      return $data['_id']->{'$id'};
    }

    // TODO: Unit test this for passing null as the limit would mean no projection.
    public static function query($collection, $query, $projection = null) {
      return $collection->find($query, $projection);
    }

    public static function update($collection, $query, $update) {
      return $collection->update($query, $update);
    }

    public static function remove($collection, $query) {
      return $collection->remove($query);
    }
  }

  ////////////////
  // Below has classes:
  // DBQuery - performs (query, projection)
  // DBUpdateQuery - performs (query, $set)
  // DBRemoveQuery - performs (query) -> remove
  ////////////////

  class DBQuery extends DBExecute {
    public function __construct($collection) {
      $this->collection = $collection;
    }

    public function toQuery($name, $val) {
      $this->query[$name] = $val;
      return $this;
    }

    public function toTextQuery($text) {
      $this->query['$text'] = array('$search' => $text);
      return $this;
    }

    /**
     * Add this field to the projection.
     */
    public function projectField($name) {
      $this->projection[$name] = true;
      return $this;
    }

    /**
     * Just project _id field.
     */
    // TODO: Unit test this to make sure when we actually run the query, just
    // passing in an empty array limits the projection to just _id.
    public function projectId() {
      $this->projection = array();
    }

    /**
     * Returns single document if found, null if no document with _id $id.
     */
    public function queryForId($id) {
      $this->query['_id'] = new MongoId($id);
      return $this;
    }

    /**
     * Run the query and return the results.
     */
    public function run() {
      if ($this->projection === null) {
        self::query($this->collection, $this->query);
      } else {
        self::query($this->collection, $this->query, $this->projection);
      }
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getQuery() {
      return $this->query;
    }

    protected $collection;

    protected $query = array();
    protected $projection = null;
  }

  class DBUpdateQuery extends DBQuery {
    public function toUpdate($name, $newval) {
      $this->update[$name] = $newval;
      return $this;
    }

    public function run() {
      // This an update, so run an update, and return success/failure.
      self::update(
        $this->collection, $this->query, array('$set' => $this->update));
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getUpdate() {
      return $this->update;
    }

    private $update = array();
  }

  class DBRemoveQuery extends DBQuery {
    public function run() {
      // Run the remove query, always returns true.
      self::remove($this->collection, $this->query);
    }
  }
?>