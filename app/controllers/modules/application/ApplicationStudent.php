<?php
  interface ApplicationStudentInterface {
    /**
     * Student saves application, so create it as a new saved application.
     * Need to make sure student has not already saved an application for the
     * job.
     */
    public static function save(MongoId $jobId,
                                MongoId $studentId,
                                array $questions);

    /**
     * Student edits a saved application, so just update the question answers.
     * Make sure the student has permission, and that the application is not
     * already submitted.
     */
    public static function edit(MongoId $applicationId, array $questions);

    /**
     * Student submits saved application, so mark it as submitted.
     * Make sure the student has permission.
     */
    public static function submitSaved(MongoId $applicationId);

    /**
     * Student submits a new application (not already saved), so create a
     * submitted application.
     */
    public static function submitNew(MongoId $jobId,
                                     MongoId $studentId,
                                     array $questions);

    /**
     * Deletes a saved application.
     * Make sure the student has permission, and that the application is not
     * already submitted.
     */
    public static function deleteSaved($id);

    public function __construct(array $data);
    public function getId();
    public function getJobId();
    public function getStudentId();
    public function setId(MongoId $id);

    // $data is an associative array containing a subset of these keys:
    // - _id (optional)
    // - job (required)
    // - student (required)
    // - array of questions (optional)
    //   - saved as list of question _id-answer pairs
    // - submitted? (required)
  }

  class ApplicationStudent extends Application
                           implements ApplicationStudentInterface {
    public static function save(MongoId $jobId,
                                MongoId $studentId,
                                array $questions) {
      return self::create($jobId, $studentId, $questions, false);
    }

    public static function edit(MongoId $applicationId, array $questions) {
      $applicationData = ApplicationModel::getById($applicationId);
      $application = new ApplicationStudent($applicationData);
      $applicationId = $application->getId();
      $jobId = $application->getJobId();

      $applicationQuestions = JobModel::getApplicationQuestionIds($jobId);
      $newQuestions = self::pruneQuestionsByIdSet(
        $questions, $applicationQuestions);

      ApplicationModel::replaceQuestionsField($applicationId, $newQuestions);
    }

    public static function submitSaved(MongoId $applicationId) {
      // Mark the application as submitted.
      ApplicationModel::markAsSubmitted($applicationId);

      $applicationData = ApplicationModel::getById($applicationId);
      $application = new ApplicationStudent($applicationData);

      self::saveStudentAnswers($application);
    }

    public static function submitNew(MongoId $jobId,
                                     MongoId $studentId,
                                     array $questions) {
      $application = self::create($jobId, $studentId, $questions, false);

      self::saveStudentAnswers($application);

      return $application;
    }

    public static function deleteSaved($id) {
      // Ask model to delete application by id and return whatever the model
      // function returns.
      return ApplicationModel::deleteById($id);
    }

    /**
     * Creates a student application to be inserted into the 'applications'
     * collection.
     * Need to make sure student has not already saved an application for the
     * job.
     */
    private static function create(MongoId $jobId,
                                   MongoId $studentId,
                                   array $questions,
                                   $submitted) {
      // Retrieve application list from $jobId.
      $applicationQuestions = JobModel::getApplicationQuestionIds($jobId);

      if ($applicationQuestions == null) {
        // The application doesn't exist, why is the student trying to save it?
        return null;
      }

      // Build question-answer pairs.
      $savedQuestions = self::pruneQuestionsByIdSet(
        $questions, $applicationQuestions);

      // Save the application.
      $application = new ApplicationStudent(array(
        'jobid' => $jobId,
        'studentid' => $studentId,
        'questions' => $savedQuestions,
        'submitted' => $submitted
      ));
      $id = ApplicationModel::insert($application->getData());
      $application->setId($id);

      // Return created application.
      return $application;
    }

    /**
     * Update a student document's 'answers' field with new saved answers.
     */
    private static function saveStudentAnswers(Application $application) {
      $studentId = $application->getStudentId();
      $questions = $application->getQuestions();

      // Retrieves the question-answer pairs.
      $answers = StudentModel::getAnswers($studentId);

      // Loop through $questions, replace answer for the corresponding question
      // in $answers, or append to $answers if it is not in $answers.
      $answersHash = arrayToHashByKey($answers, '_id', 'index');

      foreach ($questions as $question) {
        $questionId = $question['_id'];
        $newAnswer = $question['ans'];

        if (isset($answersHash[$questionId])) {
          $index = $answersHash[$questionId];
          $answers[$index]['ans'] = $newAnswer;
        } else {
          $answers[] = array('_id' => $questionId, 'ans' => $newAnswer);
        }
      }

      StudentModel::replaceAnswers($studentId, $answers);
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(array $data) {
      if (isset($data['_id'])) {
        $this->data['_id'] = new MongoId($data['_id']);
      }
      $this->data['jobid'] = new MongoId($data['jobid']);

      if (isset($data['questions'])) {
        // Process into list of question id-answer pairs.
        foreach ($data['questions'] as $question) {
          $id = new MongoId($question['_id']);
          $this->data['questions'][$id] = array(
            '_id' => $id,
            'answer' => $question['answer']
          );
        }
      }

      $this->data['questions'] =
        isset($data['questions']) ? clean($data['questions']) : array();
      $this->data['studentid'] = new MongoId($data['studentid']);
      $this->data['submitted'] = $data['submitted'] === true;
    }

    public function getId() {
      return isset($this->data['_id']) ? $this->data['_id'] : null;
    }

    public function getJobId() {
      return $this->data['jobid'];
    }

    public function getStudentId() {
      return $this->data['studentid'];
    }

    public function setId(MongoId $id) {
      $this->data['_id'] = $id;
    }

    private $data;
  }
?>