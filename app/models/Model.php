<?php
  class Model {
    // Database types that indicate which database a Model connects to.
    const DB_STUDENTS = 0;
    const DB_INTERNSHIPS = 1;

    public static $test = false;

    /**
     * Construct singleton to initialize the MongoDB connection.
     * MUST be called on children before using static calls.
     */
    public function __construct($dbType, $collectionName) {
      self::connect($dbType);

      $dbName = self::dbTypeToName($dbType);
      return self::getCollection($dbType, $collectionName);
    }

    public function __destruct() {
      // We drop any testing collections.
      if (self::$test) {
        mongo_ok(self::$collection->drop());
      }
    }

    protected static function checkReady() {
      invariant(isset(self::$collection));
    }

    /**
     * Connect to the MongoDB at $uri and puts the db $dbname into the $dbs
     * table.
     */
    private static function connect($dbType) {
      if (isset(self::$dbs[$dbType])) return;

      $uri = self::dbTypeToUri($dbType);
      $m = self::connectToUri($uri);

      $dbName = self::dbTypeToName($dbType);
      self::$dbs[$dbName] = $m->$dbName;
    }

    private static function getCollection($dbType, $collectionName) {
      if (self::$test) {
        $collectionName .= '_test';
      }

      $dbName = self::dbTypeToName($dbType);
      invariant(isset(self::$dbs[$dbName]));
      return self::$dbs[$dbName]->$collectionName;
    }

    private static function dbTypeToName($dbType) {
      switch ($dbType) {
        case self::DB_STUDENTS: return $GLOBALS['dbnamestudent'];
        case self::DB_INTERNSHIPS: return $GLOBALS['dbname'];
        default: invariant(false);
      }
    }

    private static function dbTypeToUri($dbType) {
      switch ($dbType) {
        case self::DB_STUDENTS: return $GLOBALS['dburistudent'];
        case self::DB_INTERNSHIPS: return $GLOBALS['dburi'];
        default: invariant(false);
      }
    }

    private static function connectToUri($uri) {
      try {
        $m = new MongoClient($uri);
      } catch (MongoConnectionException $e) {
        trigger_error('Mongodb not available');
      }
      return $m;
    }

    // Stores the db references to retrieve collections.
    private static $dbs = array();
  }
?>