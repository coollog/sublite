<?php
  class DBQuery {
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
        Model::query($this->collection, $this->query);
      } else {
        Model::query($this->collection, $this->query, $this->projection);
      }
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getQuery() {
      return $this->query;
    }

    private $collection;

    private $query = array();
    private $projection = null;
  }

  class DBUpdateQuery extends DBQuery {
    public function toUpdate($name, $newval) {
      $this->update[$name] = $newval;
      return $this;
    }

    public function run() {
      // This an update, so run an update, and return success/failure.
      Model::update($this->collection, $query, array('$set' => $update));
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
      Model::remove($this->collection, $query);
    }
  }
?>