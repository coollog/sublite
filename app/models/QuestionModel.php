<?php
  interface QuestionModelInterface {
    public function __construct();
    public static function getById(MongoId $id);
    public static function getAllVanilla();
    public static function getByText($text);
    public static function getByExactText($text);
    public static function deleteById(MongoId $id);
    public static function exists(MongoId $id);
    public static function insert(array $data);
  }

  class QuestionModel extends Model implements QuestionModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      static::$collection = parent::__construct(self::DB_TYPE, 'questions');
      mongo_ok(static::$collection->createIndex(array('text' => 'text')));
    }

    public static function getById(MongoId $id) {
      self::checkReady();

      $query = (new DBQuery(static::$collection))->queryForId($id);
      return $query->run();
    }

    public static function getAllVanilla() {
      self::checkReady();

      $query = (new DBQuery(static::$collection))->toQuery('vanilla', true);
      invariant(count($query->getQuery()) == 1);
      invariant($query->getQuery()['vanilla'] == true);
      return $query->run();
    }

    public static function getByText($text) {
      self::checkReady();

      $query = (new DBQuery(static::$collection))->toTextQuery($text);
      return $query->run();
    }

    public static function getByExactText($text) {
      self::checkReady();

      $query = (new DBQuery(static::$collection))->toQuery('text', $text);
      return $query->run();
    }

    public static function deleteById(MongoId $id) {
      self::checkReady();

      $query = (new DBRemoveQuery(static::$collection))->queryForId($id);
      return $query->run();
    }

    public static function exists(MongoId $id) {
      self::checkReady();

      $query = (new DBQuery(static::$collection))->queryForId($id)->justId();
      return $query->run();
    }

    public static function insert(array $data) {
      self::checkReady();

      $insert = (new DBInsert(static::$collection))->setData($data);

      $id = $insert->run();
      invariant($id !== null);

      return $id;
    }
  }
?>