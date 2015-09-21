<?php
  class Model {
    /**
     * Connect to the MongoDB at $uri and puts the db $dbname into the $dbs
     * table.
     */
    public static function connect($uri, $dbName) {
      if (isset(self::dbs[$dbName])) return;

      try {
        $m = new MongoClient($uri);
      } catch (MongoConnectionException $e) {
        trigger_error('Mongodb not available');
      }
      $db = $m->$dbName;
      self::dbs[$dbName] = $db;
    }

    public static function getCollection($dbName, $collectionName) {
      return $dbs[$dbName]->$collectionName;
    }

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

    // Stores the db references to retrieve collections.
    private static $dbs = array();

    ////////////////////////
    // Compatibility layer:
    ////////////////////////

    public function __construct($collectionname) {
      try {
        // Setup database
        $m = new MongoClient($GLOBALS['dburi']);
      } catch(MongoConnectionException $e) {
        trigger_error('Mongodb not available');
      }
      $db = $m->$GLOBALS['dbname'];
      $this->collection = $db->$collectionname;
    }

    // Database variables
    protected $collection;
  }
?>