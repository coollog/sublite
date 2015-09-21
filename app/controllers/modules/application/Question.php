<?php
  //TODO Remove this later
  require_once("../../../models/QuestionModel.php");
  class Question {
    public static function getAllVanilla() {

      // Issue query to get all questions with vanilla flag on.
      $query = (new QuestionQuery())->toQuery('vanilla', true);
      assert(count($query->getQuery()) == 1);
      assert($query->getQuery()['vanilla'] == true);
      $results = $query->run();

      // Parse and return data from query and make array of questions with data
      return self::parseRawData($results);
    }

    public static function search($text) {
      // Issue query to model
      $query = new QuestionQuery();
      $query->toTextQuery($text);
      $results = $query->run();

      // Create array of question(s) from returned data and return it
      return self::parseRawData($results);
    }

    public static function createCustom() {
      // return (call create with custom parameter)
    }

    public static function createVanilla() {
      // return (call create with vanilla parameter)
    }

    public static function getById($id) {
      // Ask model to get it
      
      // If model can't get it, return null

      // If model can get it, parse the raw data and return question
    }

    public static function delete($id) {
      // Ask model to delete question by id

      // return whatever the model function returns
    }

    /**
     * // TODO Make this documentation better later
     * take in ARRAY of raw question data from model and create array of
     * Question instances.
     */
    private static function parseRawData($data) {
      $questions = array();
      foreach ($data as $rawQuestion) {
        $questions[] = new Question($rawQuestion);
      }
      return $questions;
    }

    private static function create() {
      // Construct question with parameters

      // Check if question already exists (model function). If so, return question

      // Pass (question object or raw data?) to model to store in database with
      // custom flag

      // Return created question
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct($data) {
      if (isset($data['_id'])) {
        $this->data['_id'] = clean($data['_id']);
      }
      $this->data['text'] = clean($data['text']);
      $this->data['recruiter'] = clean($data['recruiter']);
      if (isset($data['uses'])) {
        $this->data['uses'] = clean($data['uses']);
      } else {
        $this->data['uses'] = array();
      }
      $this->data['vanilla'] = $data['vanilla'] == true;
    }

    public function getData() {
      return $this->data;
    }

    public function getId() {
      return isset($this->data['_id']) ? $this->data['_id'] : null;
    }

    public function getText() {
      return $this->data['text'];
    }

    public function getRecruiter() {
      return $this->data['recruiter'];
    }

    public function getUses() {
      return isset($this->data['uses']) ? $this->data['uses'] : null;
    }

    public function getVanilla() {
      return $this->data['text'];
    }

    // $data is an associative array containing a subset of these keys:
    // _id: (optional) corresponds to the mongoID
    // text: (required) the question text
    // recruiter: (required) recruiter ID
    // uses: (optional) an array of app ID's that have used this question
    // vanilla: (required) whether or not the question is vanilla
    private $data;
  }

  Question::getAllVanilla();
?>