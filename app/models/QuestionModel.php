<?php
  class QuestionModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    protected static $collection;

    public function __construct() {
      self::$collection = parent::__construct(self::DB_TYPE, 'questions');
    }

    public static function getById($id) {
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

    public static function deleteById($id) {
      checkReady();

      $query = (new DBRemoveQuery(self::$collection))->queryForId($id);
      return $query->run();
    }

    public static function exists($id) {
      checkReady();

      $query = (new DBQuery(self::$collection))->queryForId($id)->justId();
      return $query->run();
    }
  }
?>