<?php
  interface SubletModelInterface {
    public function __construct();

    public static function save(array $data);
    public static function find(array $query, array $fields);
    public static function last($n);
    public static function getByStudent(MongoId $studentId);
  }

  class SubletModel extends Model implements SubletModelInterface {
    const DB_TYPE = parent::DB_STUDENTS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'listings');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(['student' => 1]));
      mongo_ok(self::$collection->createIndex(['publish' => 1]));
    }

    public static function save(array $data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function find(array $query=array(), array $fields=array()) {
      return self::$collection->find($query, $fields);
    }
    public static function last($n=1) {
      parent::last($n, ['publish' => true]);
    }
    public static function getByStudent(MongoId $studentId) {
      $query = (new DBQuery(self::$collection))->toQuery('student', $studentId);
      return $query->run();
    }

    protected static $collection;
  }

  new SubletModel();
?>