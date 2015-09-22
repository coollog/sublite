<?php
  //TODO Remove this later
  require_once($GLOBALS['dirpre']."models/Model.php");
  require_once($GLOBALS['dirpre']."models/modules/DBQuery.php");
  require_once($GLOBALS['dirpre']."models/QuestionModel.php");

  //TODO Add validations
  class Question {
    public static function getAllVanilla() {
      // Issue query to get all questions with vanilla flag on.
      $results = QuestionModel::getAllVanilla();

      // Parse and return data from query and make array of questions with data
      return self::parseRawData($results);
    }

    public static function search($text) {
      // Issue query to model
      $results = QuestionModel::getByText($text);

      // Create array of question(s) from returned data and return it
      return self::parseRawData($results);
    }

    public static function createCustom($text, $recruiter) {
      // return (call create with custom parameter)
      return create($text, $recruiter, false);
    }

    public static function createVanilla($text, $recruiter) {
      // return (call create with vanilla parameter)
      return create($text, $recruiter, true);
    }

    public static function getById($id) {
      // Ask model to get it. If model can't get it, return null.
      // If model can get it, parse the raw data and return question.
      $question = QuestionModel::getById($id);

      return $question === null ? null : self::parseRawData($question);
    }

    public static function delete($id) {
      // Ask model to delete question by id and return whatever the model
      // function returns.
      return QuestionModel::deleteById($id);
    }

    /**
     * // TODO Make this documentation better later
     * Take in ARRAY or SINGLE of raw question data from model and create array
     * of Question instances.
     */
    private static function parseRawData($data) {
      if (is_array($data)) {
        $questions = array();
        foreach ($data as $rawQuestion) {
          $questions[] = new Question($rawQuestion);
        }
        return $questions;
      }

      // Not an array, so just parse single question.
      return new Question($data);
    }

    private static function create($text, $recruiter, $vanilla) {
      // Check if question already exists (model function). If so, return
      // the question.
      $existingQuestion = QuestionModel::getByExactText($text);
      if ($existingQuestion !== null) {
        return self::parseRawData($existingQuestion);
      }

      // Construct question with parameters.
      $question = new Question(array(
        'text' => $text,
        'recruiter' => $recruiter,
        'vanilla' => $vanilla
      ));

      // Pass (question object or raw data?) to model to store in database with
      // custom flag
      QuestionModel::insert($question->getData());

      // Return created question.
      return $question;
    }

    //**********************
    // non-static functions
    //**********************

    /**
     * Construct a Question instance by passing in an ass. array of the data
     * for the question.
     * Cleans and prunes the ass array to be just the data necessary.
     * See the declaration of $this->data below.
     */
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
?>