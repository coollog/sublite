<?php
  interface QuestionModelInterface {
    public function __construct();
    public static function getById(MongoId $id);
    public static function getAllVanilla();
    public static function getByText($text);
    public static function getByExactText($text);
    public static function deleteById(MongoId $id);
    public static function exists(MongoId $id);
  }

  class QuestionModel extends Model implements QuestionModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    protected static $collection;

    public function __construct() {
      self::$collection = parent::__construct(self::DB_TYPE, 'questions');
    }

    public static function getById(MongoId $id) {
      checkReady();

      $query = (new DBQuery(self::$collection))->queryForId($id);
      return $query->run();
    }

    public static function getAllVanilla() {
      checkReady();

      $query = (new DBQuery(self::$collection))->toQuery('vanilla', true);
      invariant(count($query->getQuery()) == 1);
      invariant($query->getQuery()['vanilla'] == true);
      return $query->run();
    }

    public static function getByText($text) {
      checkReady();

      $query = (new DBQuery(self::$collection))->toTextQuery($text);
      return $query->run();
    }

    public static function getByExactText($text) {
      checkReady();

      $query = (new DBQuery(self::$collection))->toQuery('text', $text);
      return $query->run();
    }

    public static function deleteById(MongoId $id) {
      checkReady();

      $query = (new DBRemoveQuery(self::$collection))->queryForId($id);
      return $query->run();
    }

    public static function exists(MongoId $id) {
      checkReady();

      $query = (new DBQuery(self::$collection))->queryForId($id)->justId();
      return $query->run();
    }
  }
?>