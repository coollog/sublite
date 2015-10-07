<?php
  require_once($GLOBALS['dirpre']."models/modules/DBQuery.php");

  interface ModelInterface {
    /**
     * Construct singleton to initialize the MongoDB connection.
     * MUST be called on children before using static calls.
     */
    public function __construct($dbType, $collectionName);

    public function __destruct();
    public static function myCollection();
    public static function insert(array $data);
    public static function deleteById(MongoId $id);
    public static function getById(MongoId $id, array $projection);
  }

  class Model {
    // Database types that indicate which database a Model connects to.
    const DB_STUDENTS = 0;
    const DB_INTERNSHIPS = 1;

    public static $test = false;

    public static function myCollection() {
      if (self::$test) {
        return substr(static::$collection->getName(), 0, -5);
      }

      return static::$collection;
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
      $result = $query->run();
      return $result['n'];
    }

    public static function getById(MongoId $id, array $projection = array()) {
      self::checkReady();

      $query = (new DBQuery(static::$collection))
        ->queryForId($id)->setProjection($projection);

      return $query->findOne();
    }

    protected static function checkReady() {
      invariant(isset(static::$collection), 'collection not set up');
    }

    protected static function queryForId(MongoId $id) {
      return (new DBQuery(static::$collection))->queryForId($studentId);
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

    protected static $collection;

    public function __construct($dbType, $collectionName) {
      self::connect($dbType);

      $dbName = self::dbTypeToName($dbType);
      static::$collection = self::getCollection($dbType, $collectionName);
      return static::$collection;
    }

    public function __destruct() {
      // We drop any testing collections.
      if (self::$test) {
        if (isset(static::$collection)) {
          if (endsWith(static::$collection->getName(), '_test')) {
            mongo_ok(static::$collection->drop());
          }
        }
      }
    }
  }
?>