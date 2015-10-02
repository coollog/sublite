<?php
  interface ApplicationInterface {
    public static function delete(MongoId $id);
    public static function updateQuestions(MongoId $recruiter,
                                           Application $application,
                                           array $questions);

    public function __construct();
    public function getJobId();
    public function getQuestions();
    public function getRecruiter();
    public function setQuestions();
  }

  interface ApplicationJobInterface {
    public static function create(MongoId $job, array $questions);

    public function __construct(MongoId $job, array $data);
  }

  interface ApplicationStudentInterface {
    public static function create(MongoId $job, array $questions, $submitted);
    public static function edit();
    public static function delete($id);

    public function getId();
    public function getStudentId();
    public function setId();
  }

  class Application implements ApplicationInterface {


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

    public function getData() {
      return $this->data;
    }

    public function getQuestions() {
      return $this->data['questions'];
    }

    public function setQuestions(array $questions) {
      $this->data['questions'] = $questions;
    }
  }

  class ApplicationJob extends Application implements ApplicationJobInterface {
    /**
     * Creates an application as the 'application' field in a job document.
     */
    public static function createOrUpdate(MongoId $jobId, array $questions) {
      $application = new ApplicationJob($jobId, array(
        'questions' => $questions,
      ));
      $setSuccess =
        ApplicationModel::setJobApplication($jobId, $application->getData());

      if (!$setSuccess) return false;

      // Update student applications saved for this job to be new list of
      // questions.
      ApplicationModel::updateSavedApplications($jobId, $application->getData());

      return true;
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(MongoId $job, array $data) {
      $this->jobId = $job;

      $this->data['questions'] =
        isset($data['questions']) ? clean($data['questions']) : array();
      }
    }

    public function getJobId() {
      return $this->jobId;
    }

    // - array of questions (optional) - saved as list of question ids
    private $data;

    // The _id of the job associated with the application.
    private $jobId;
  }

  class ApplicationStudent extends Application
                           implements ApplicationStudentInterface {
    /**
     * Creates a student application to be inserted into the 'applications'
     * collection.
     */
    public static function create(MongoId $job,
                                  array $questions,
                                  $submitted) {
      $application = new ApplicationStudent(array(
        'job' => $job,
        'questions' => $questions,
        'submitted' => $submitted
      ));
      $id = ApplicationModel::insert($application->getData());
      $application->setId($id);

      // Return created application.
      return $application;
    }

    public static function edit() {

    }

    public static function delete($id) {
      // Ask model to delete application by id and return whatever the model
      // function returns.
      return ApplicationModel::deleteById($id);
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(array $data) {
      if (isset($data['_id'])) {
        $this->data['_id'] = new MongoId($data['_id']);
      }
      $this->data['jobid'] = new MongoId($data['job']);

      if (isset($data['questions'])) {
        // Process into list of question id-answer pairs.
        foreach ($data['questions'] as $question) {
          $this->data['questions'][] = array(
            '_id' => new MongoId($question['_id']),
            'answer' => $question['answer']
          );
        }
      }

      $this->data['questions'] =
        isset($data['questions']) ? clean($data['questions']) : array();
      if (isset($data['student'])) {
        $this->data['studentid'] = new MongoId($data['student']);
      }
      $this->data['submitted'] = $data['submitted'] === true;
    }

    public function getId() {
      return isset($this->data['_id']) ? $this->data['_id'] : null;
    }

    public function getJobId() {
      return $this->data['jobid'];
    }

    public function getStudentId() {
      return isset($this->data['studentid']) ? $this->data['studentid'] : null;
    }

    public function setId(MongoId $id) {
      $this->data['_id'] = $id;
    }

    // - _id (optional)
    // - job (required)
    // - student (required)
    // - array of questions (optional)
    //   - saved as list of question _id-answer pairs
    // - submitted? (required)
    private $data;
  }
?>