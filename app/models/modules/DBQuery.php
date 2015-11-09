<?php
  interface DBExecuteInterface {
    public function __construct(MongoCollection $collection);

    /**
     * Inserts the document $data into the database. $data must be nonempty.
     * Returns the document _id, or null if error.
     */
    public function insert(array $data);

    public function query();
    public function queryForOne();
    public function update(array $update);
    public function remove();
  }

  // DBQuery - performs (query, projection)
  interface DBQueryInterface {
    public function toQuery($name, $val);
    public function toNotQuery($name, $val);
    public function limit($n);

    /**
     * Run a text search on $text.
     */
    public function textSearch($text);

    /**
     * Add this field to the projection.
     */
    public function projectField($name);

    public function setProjection(array $projection);

    /**
     * Just project _id field.
     */
    // TODO: Unit test this to make sure when we actually run the query, just
    // passing in an empty array limits the projection to just _id.
    public function projectId();

    /**
     * Returns single document if found, null if no document with _id $id.
     */
    public function queryForId(MongoId $id);

    /**
     * Runs the query to just find and return one document.
     * Returns null if none exists.
     */
    public function findOne();

    /**
     * Run the query and return the results.
     */
    public function run();

    /**
     * VISIBLE FOR TESTING
     */
    public function getQuery();
  }

  interface DBUpdateQueryInterface {
    /**
     * Set individual $set field.
     */
    public function toUpdate($name, $newval);

    /*
     * Set individual $inc field.
     */
    public function toAdd($name, $addval);

    /**
     * Set individual $push/$pull field.
     */
    public function toPush($name, $val);
    public function toPull($name, $val);

    /**
     * Set entire $set data.
     */
    public function setUpdate($update);

    public function run();

    /**
     * VISIBLE FOR TESTING
     */
    public function getUpdate();
  }

  interface DBRemoveQueryInterface {
    public function run();
  }

  interface DBInsertInterface {
    public function setData(array $data);
    public function run();
  }

  /**
   * Contains functions for performing commands on the DB.
   */
  class DBExecute implements DBExecuteInterface {
    private static function cursorToArray(MongoCursor $cursor) {
      $docs = [];
      foreach ($cursor as $doc) {
        $docs[] = $doc;
      }
      return $docs;
    }

    public function __construct(MongoCollection $collection) {
      $this->collection = $collection;
    }

    public function insert(array $data) {
      if (count($data) == 0) {
        return null;
      }

      $this->collection->insert($data);
      return $data['_id'];
    }

    public function query() {
      $cursor = $this->collection->find($this->query, $this->projection);

      if (!empty($this->sort)) {
        $cursor->sort($this->sort);
      }
      if (isset($this->limit)) {
        $cursor->limit($this->limit);
      }

      return self::cursorToArray($cursor);
    }

    public function queryForOne() {
      return $this->collection->findOne($this->query, $this->projection);
    }

    public function update(array $update) {
      return $this->collection->update($this->query, $update);
    }

    public function remove() {
      return $this->collection->remove($this->query);
    }

    protected $collection;
    protected $query = [];
    protected $projection = [];
    protected $sort = [];
    protected $limit;
  }

  ////////////////
  // Below has classes:

  // DBUpdateQuery - performs (query, $set)
  // DBRemoveQuery - performs (query) -> remove
  ////////////////

  class DBQuery extends DBExecute implements DBQueryInterface {
    public function toQuery($name, $val) {
      $this->query[$name] = $val;
      return $this;
    }

    public function textSearch($text) {
      $this->query['$text'] = ['$search' => $text];
      $this->projection['score'] = ['$meta' => 'textScore'];
      $this->sort['score'] = ['$meta' => 'textScore'];
      // We reverse to have score in descending order.
      return array_reverse(self::run());
    }

    public function toNotQuery($name, $val) {
      $this->query[$name] = ['$ne' => $val];
      return $this;
    }

    public function limit($n) {
      $this->limit = $n;
      return $this;
    }

    public function setProjection(array $projection) {
      $this->projection = $projection;
      return $this;
    }

    public function projectField($name) {
      $this->projection[$name] = true;
      return $this;
    }

    public function projectId() {
      $this->projection = ['_nonexistent' => 1];
      return $this;
    }

    public function queryForId(MongoId $id) {
      $this->query['_id'] = $id;
      return $this;
    }

    public function findOne() {
      return self::queryForOne();
    }

    public function run() {
      return self::query();
    }

    public function getQuery() {
      return $this->query;
    }
  }

  class DBUpdateQuery extends DBQuery implements DBUpdateQueryInterface {
    public function toUpdate($name, $newval) {
      $this->update[$name] = $newval;
      return $this;
    }

    public function toAdd($name, $addval) {
      $this->inc[$name] = $addval;
      return $this;
    }

    public function toPush($name, $val) {
      $this->push[$name] = $val;
      return $this;
    }

    public function toPull($name, $val) {
      $this->pull[$name] = $val;
      return $this;
    }

    public function setUpdate($update) {
      $this->update = $update;
    }

    public function run() {
      // This an update, so run an update, and return success/failure.
      $data = [];
      if (!empty($this->update)) {
        $data['$set'] = $this->update;
      }
      if (!empty($this->inc)) {
        $data['$inc'] = $this->inc;
      }
      if (!empty($this->push)) {
        $data['$push'] = $this->push;
      }
      if (!empty($this->pull)) {
        $data['$pull'] = $this->pull;
      }
      self::update($data);
    }

    public function getUpdate() {
      return $this->update;
    }

    private $update = [];
    private $inc = [];
    private $pull = [];
    private $push = [];
  }

  class DBRemoveQuery extends DBQuery implements DBRemoveQueryInterface {
    public function run() {
      // Run the remove query, always returns true.
      return self::remove();
    }
  }

  class DBInsert extends DBExecute implements DBInsertInterface {
    public function setData(array $data) {
      $this->data = $data;
      return $this;
    }

    public function run() {
      invariant(isset($this->data));

      return self::insert($this->data);
    }

    private $data;
  }
?>