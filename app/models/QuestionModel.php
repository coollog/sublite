<?php
  class QuestionModel extends Model {
    public static function getById($id) {
      $query = (new DBQuery())->queryForId($id);
      return $query->run();
    }

    public static function getAllVanilla() {
      $query = (new DBQuery())->toQuery('vanilla', true);
      assert(count($query->getQuery()) == 1);
      assert($query->getQuery()['vanilla'] == true);
      return $query->run();
    }

    public static function getByText($text) {
      $query = (new DBQuery())->toTextQuery($text);
      return $query->run();
    }

    public static function getByExactText($text) {
      $query = (new DBQuery())->toQuery('text', $text);
      return $query->run();
    }

    public static function deleteById($id) {
      $query = (new DBRemoveQuery())->queryForId($id);
      return $query->run();
    }

    public static function exists($id) {
      $query = (new DBQuery())->queryForId($id)->justId();
      return $query->run();
    }
  }
?>