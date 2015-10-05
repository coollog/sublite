<?php
  interface QuestionInterface {
    public static function getAllVanilla();
    public static function search($text);
    public static function createCustom($text, MongoId $recruiter);
    public static function createVanilla($text, MongoId $recruiter);
    public static function getById(MongoId $id);
    public static function delete(MongoId $id);

    /**
     * Construct a Question instance by passing in an ass. array of the data
     * for the question.
     * Cleans and prunes the ass array to be just the data necessary.
     * See the declaration of $this->data below.
     */
    public function __construct(array $data);
    public function getData();
    public function getId();
    public function getText();
    public function getRecruiter();
    public function getUses();
    public function getVanilla();
    public function setId(MongoId $id);

    // $data is an associative array containing a subset of these keys:
    // _id: (optional) corresponds to the mongoID
    // text: (required) the question text
    // recruiter: (required) recruiter ID
    // uses: (optional) an array of app ID's that have used this question
    // vanilla: (required) whether or not the question is vanilla
  }

  //TODO Add validations
  class Question implements QuestionInterface {
    public static function getAllVanilla() {
      // Issue query to get all questions with vanilla flag on.
      $results = QuestionModel::getAllVanilla();

      // Parse and return data from query and make array of questions with data.
      return self::parseRawData($results);
    }

    public static function search($text) {
      // Issue query to model.
      $results = QuestionModel::getByText($text);

      // Create array of question(s) from returned data and return it.
      return self::parseRawData($results);
    }

    public static function createCustom($text, MongoId $recruiter) {
      // Return (call create with custom parameter).
      return self::create($text, $recruiter, false);
    }

    public static function createVanilla($text, MongoId $recruiter) {
      // Return (call create with vanilla parameter).
      return self::create($text, $recruiter, true);
    }

    public static function getById(MongoId $id) {
      // Ask model to get it. If model can't get it, return null.
      // If model can get it, parse the raw data and return question.
      $question = QuestionModel::getById($id);

      return $question === null ? null : self::parseRawData($question);
    }

    public static function delete(MongoId $id) {
      // Ask model to delete question by id and return whatever the model
      // function returns.
      return QuestionModel::deleteById($id);
    }

    /**
     * // TODO Make this documentation better later
     * Take in ARRAY or SINGLE of raw question data from model and create array
     * of Question instances.
     */
    private static function parseRawData(array $data) {
      if (count($data) == 0) {
        return array();
      }

      if (!isAssoc($data)) {
        $questions = array();
        foreach ($data as $rawQuestion) {
          $questions[] = new Question($rawQuestion);
        }
        return $questions;
      }

      // Not an array, so just parse single question.
      return new Question($data);
    }

    private static function create($text, MongoId $recruiter, $vanilla) {
      // Check if question already exists (model function). If so, return
      // the question.
      $existingQuestion = QuestionModel::getByExactText($text);
      if (count($existingQuestion) > 0) {
        return self::parseRawData($existingQuestion);
      }

      // Construct question with parameters.
      $question = new Question(array(
        'text' => $text,
        'recruiter' => $recruiter,
        'vanilla' => $vanilla
      ));

      // Pass (question object or raw data?) to model to store in database with
      // custom flag.
      $id = QuestionModel::insert($question->getData());
      $question->setId($id);

      // Return created question.
      return $question;
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(array $data) {
      if (isset($data['_id'])) {
        $this->data['_id'] = new MongoId($data['_id']);
      }
      $this->data['text'] = clean($data['text']);
      $this->data['recruiter'] = new MongoId($data['recruiter']);
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
      return $this->data['vanilla'];
    }

    public function setId(MongoId $id) {
      $this->data['_id'] = $id;
    }

    private $data;
  }
?>