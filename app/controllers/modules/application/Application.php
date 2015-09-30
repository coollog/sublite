<?php
  interface ApplicationInterface {
    public static function create(MongoId $recruiter,
                                  MongoId $job,
                                  array $questions);
    public static function delete(MongoId $id);
    public static function updateQuestions(MongoId $recruiter,
                                           Application $application,
                                           array $questions);

    public function __construct();
    public function getQuestions();
    public function getRecruiter();
    public function getId();
    public function getJobId();
    public function getStudentId();
    public function setId();
    public function setQuestions();
  }

  class Application implements ApplicationInterface {
    public static function create(MongoId $recruiter,
                                  MongoId $job,
                                  array $questions) {
      $application = new Application(array(
        'recruiter' => $recruiter,
        'job' => $job,
        'questions' => $questions
      ));
      $id = ApplicationModel::insert($application->getData());
      $application->setId($id);

      // Return created application
      return $application;
    }

    public static function delete($id) {
      // Ask model to delete application by id and return whatever the model
      // function returns.
      return ApplicationModel::deleteById($id);
    }

    public static function updateQuestions(MongoId $recruiter,
                                           MongoId $application,
                                           array $questions) {
      if (ApplicationModel::getById($application)->getRecruiter != $recruiter) {
        // TODO Fail
      }
      $application->setQuestions($questions);
      // TODO Finish
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(array $data) {
      if (isset($data['_id'])) {
        $this->data['_id'] = new MongoId($data['_id']);
      }
      $this->data['job'] = new MongoId($data['job']);
      $this->data['questions'] =
          isset($data['questions']) ? clean($data['questions']) : array();
      if (isset($data['student'])) {
        $data['student'] = new MongoId($data['student']);
      }
    }

    public function getQuestions() {
      return $this->data['questions'];
    }

    public function getId() {
      return isset($this->data['_id']) ? $this->data['_id'] : null;
    }

    public function getJobId() {
      return $this->data['job'];
    }

    public function getStudentId() {
      return isset($this->data['student']) ? $this->data['student'] : null;
    }

    public function setId(MongoId $id) {
      $this->data['_id'] = $id;
    }

    public function setQuestions(array $questions) {
      $this->data['questions'] = $questions;
    }

    // - job (required)
    // - _id (optional)
    // - array of questions (optional)
    // - student (optional)
    private $data;
  }
?>