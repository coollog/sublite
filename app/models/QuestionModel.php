<?php
  class QuestionModel extends Model {
    /**
     * This function MUST be called to initialize the MongoDB connection.
     */
    public static function init() {
      self::connect($GLOBALS['dburi'], $GLOBALS['dbname']);
      self::collection = self::getCollection($GLOBALS['dbname'], 'questions');
    }

    public static function getById($id) {
      $query = (new DBQuery($this->collection))->queryForId($id);
      return $query->run();
    }

    public static function getAllVanilla() {
      $query = (new DBQuery($this->collection))->toQuery('vanilla', true);
      assert(count($query->getQuery()) == 1);
      assert($query->getQuery()['vanilla'] == true);
      return $query->run();
    }

    public static function getByText($text) {
      $query = (new DBQuery($this->collection))->toTextQuery($text);
      return $query->run();
    }

    public static function getByExactText($text) {
      $query = (new DBQuery($this->collection))->toQuery('text', $text);
      return $query->run();
    }

    public static function deleteById($id) {
      $query = (new DBRemoveQuery($this->collection))->queryForId($id);
      return $query->run();
    }

    public static function exists($id) {
      $query = (new DBQuery($this->collection))->queryForId($id)->justId();
      return $query->run();
    }

    private static $collection;
  }
?>