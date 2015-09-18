<?php
  class QuestionModel {
    public static function get($query) {
      // TODO
    }
  }

  class QuestionQuery {
    public function __construct() {}

    public function toQuery($name, $val) {
      $this->query[$name] = $val;
      return $this;
    }

    public function toTextQuery($text) {
      $this->query['$text'] = array('$search' => $text);
      return $this;
    }

    public function toUpdate($name, $newval) {
      $this->update[$name] = $newval;
      return $this;
    }

    public function run() {
      if (count($update) == 0) {
        // Run the query and return the results.
      } else {
        // This an update, so run an update, and return success/failure.
        update($query, array('$set' => $update));
      }
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getQuery() {
      return $this->query;
    }

    /**
     * VISIBLE FOR TESTING
     */
    public function getUpdate() {
      return $this->update;
    }

    private $query = array();
    private $update = array();
  }
?>