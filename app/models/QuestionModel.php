<?php
  class QuestionModel {
    public static function get($query) {
      // TODO
    }

    public static function getById($id) {

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

    public static function deleteById($id) {
      $query = (new DBRemoveQuery())->queryForId($id);
      return $query->run();
    }
  }

  class DBQuery {
    public function __construct() {}

    public function toQuery($name, $val) {
      $this->query[$name] = $val;
      return $this;
    }

    public function toTextQuery($text) {
      $this->query['$text'] = array('$search' => $text);
      return $this;
    }

    public function queryForId($id) {
      $this->query['_id'] = new MongoId($id);
      return $this;
    }

    public function run() {
      // Run the query and return the results.
      query($query);
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getQuery() {
      return $this->query;
    }

    private $query = array();
  }

  class DBUpdateQuery extends DBQuery {
    public function toUpdate($name, $newval) {
      $this->update[$name] = $newval;
      return $this;
    }

    public function run() {
      // This an update, so run an update, and return success/failure.
      update($query, array('$set' => $update));
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getUpdate() {
      return $this->update;
    }

    private $update = array();
  }

  class DBRemoveQuery extends DBQuery {
    public function run() {
      // Run the remove query, always returns true.
      remove($query);
    }
  }
?>