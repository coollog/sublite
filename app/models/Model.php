<?php
  require_once($GLOBALS['dirpre']."models/modules/DBQuery.php");

  interface ModelInterface {
    public function __construct($dbType, $collectionName);
    public function __destruct();
    public static function myCollection();
    public static function insert(array $data);
    public static function deleteById(MongoId $id);
    public static function getById(MongoId $id);
  }

  class Model {
    // Database types that indicate which database a Model connects to.
    const DB_STUDENTS = 0;
    const DB_INTERNSHIPS = 1;

    public static $test = false;

    protected static $collection;

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

    public static function myCollection() {
      if (self::$test) {
        return substr(self::$collection->getName(), 0, -5);
      }

      return self::$collection;
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

    public static function insert(array $data) {
      self::checkReady();

      $insert = (new DBInsert(static::$collection))->setData($data);

      $id = $insert->run();
      invariant($id !== null);

      return $id;
    }

    public static function deleteById(MongoId $id) {
      self::checkReady();

      $query = (new DBRemoveQuery(static::$collection))->queryForId($id);
      return $query->run();
    }

    public static function getById(MongoId $id) {
      self::checkReady();

      $query = (new DBQuery(static::$collection))->queryForId($id);
      return $query->run();
    }

    // Stores the db references to retrieve collections.
    private static $dbs = array();
  }
?>