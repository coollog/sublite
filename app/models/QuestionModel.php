<?php
  interface QuestionModelInterface {
    public function __construct();
    public static function getAllVanilla();
    public static function getByText($text);
    public static function getByExactText($text);
    public static function exists(MongoId $id);
  }

  class QuestionModel extends Model implements QuestionModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      self::$collection = parent::__construct(self::DB_TYPE, 'questions');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(array('text' => 'text')));
    }

    public static function getAllVanilla() {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->toQuery('vanilla', true);
      invariant(count($query->getQuery()) == 1);
      invariant($query->getQuery()['vanilla'] == true);
      return $query->run();
    }

    public static function getByText($text) {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->toTextQuery($text);
      return $query->run();
    }

    public static function getByExactText($text) {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->toQuery('text', $text);
      return $query->run();
    }

    public static function exists(MongoId $id) {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->queryForId($id)->justId();
      return $query->run();
    }

    private static $collection;
  }
?>