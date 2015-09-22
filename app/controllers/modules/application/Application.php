<?php
  class Application {
    public static function create($recruiter, $job, $questions) {

    }

    public static function delete($id) {
      // Ask model to delete application by id and return whatever the model
      // function returns.
      return ApplicationModel::deleteById($id);
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct() {

    }

    public function getQuestions() {

    }

    public function getRecruiter() {

    }

    // _id, array of questions, recruiter
    private $data;
  }
?>