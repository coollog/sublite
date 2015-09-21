<?php
  class Model {
// CONSTANTS:
    // Database types that indicate which database a Model connects to.
    const DB_STUDENTS = 0;
    const DB_INTERNSHIPS = 1;

// PUBLIC:
    // Handles the MongoClients.
    public static $dbReady = false;
    public static $db = array();

// PROTECTED:
    // Holds the MongoCollection.
    protected $collection;
    protected $test = false;

    function __construct($dbType, $collectionName, $test=false) {
      // First time, so setup the database and collection.
      if (!self::$dbReady) {
        self::$db[self::DB_INTERNSHIPS] =
          self::_connectToDB($GLOBALS['dburi'], $GLOBALS['dbname']);
        self::$db[self::DB_STUDENTS] =
          self::_connectToDB($GLOBALS['dburistudent'],
                             $GLOBALS['dbnamestudent']);
        self::$dbReady = true;
      }

      // Select the correct database depending on the $dbType.
      invariant(isset(self::$db[$dbType]),
                'Invalid dbType specificied for Model construction.');
      $db = self::$db[$dbType];

      // If testing, we add '_test' to the collection name.
      if ($test) {
        $collectionName .= '_test';
        $this->test = true;
      }
      $this->collection = $db->$collectionName;
    }

    function __destruct() {
      // We drop any testing collections.
      if ($this->test) {
        mongo_ok($this->collection->drop());
      }
    }

// PRIVATE:
    private function _connectToDB($dbURI, $dbName) {
      try {
        $m = new MongoClient($dbURI);
      } catch (MongoConnectionException $e) {
        trigger_error('Mongodb not available');
      }
      $db = $m->$dbName;
      return $db;
    }



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
  }
?>