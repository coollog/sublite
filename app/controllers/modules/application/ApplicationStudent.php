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
     * Student wishes to delete an application. Cannot delete submitted
     * applications.
     */
    public static function deleteSaved(MongoId $id);

    /**
     * Get all the unclaimed applications for a job. The applications must be
     * submitted.
     */
    public static function getUnclaimedByJob(MongoId $jobId);

    /**
     * Get all claimed applications for a job.
     * Returns a map with keys 'review', 'rejected', and 'accepted', each
     * mapping to a list of applications corresponding to those statuses.
     */
    public static function getClaimedByJob(MongoId $jobId);

    public function __construct(array $data);
    public function getId();
    public function getJobId();
    public function getStudentId();
    public function getStatus();
    public function isClaimed();
    public function setId(MongoId $id);

    // $data is an associative array containing a subset of these keys:
    // - _id (optional)
    // - job (required)
    // - student (required)
    // - array of questions (optional)
    //   - saved as list of question _id-answer pairs
    // - submitted? (required)
    // - status (optional)
  }

  class ApplicationStudent extends Application
                           implements ApplicationStudentInterface {
    // These constants are define what the 'status' field of $data means.
    const STATUS_UNCLAIMED = 0;
    const STATUS_REVIEW    = 1;
    const STATUS_REJECTED  = 2;
    const STATUS_ACCEPTED  = 3;

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

    public static function deleteSaved(MongoId $id) {
      return ApplicationModel::deleteById($id);
    }

    public static function getUnclaimedByJob(MongoId $jobId) {
      return processDataArray(ApplicationModel::getUnclaimed($jobId));
    }

    public static function getClaimedByJob(MongoId $jobId) {
      $jobs = processDataArray(ApplicationModel::getClaimed($jobId));

      $statusMap = array(
        'review' => array(),
        'rejected' => array(),
        'accepted' => array()
      );

      foreach ($jobs as $job) {
        switch ($job->getStatus()) {
          case self::STATUS_REVIEW: $key = 'review'; break;
          case self::STATUS_REJECTED: $key = 'rejected'; break;
          case self::STATUS_ACCEPTED: $key = 'accepted'; break;
          default: invariant(false);
        }
        $statusMap[$key][] = $job;
      }

      return $statusMap;
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

    private static function processDataArray(array $jobDataArray) {
      $jobs = array();
      foreach ($jobDataArray as $jobData) {
        $jobs[] = new ApplicationStudent($jobData);
      }
      return $jobs;
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
      $this->data['submitted'] = boolval($data['submitted']);
      $this->data['status'] =
        isset($data['status']) ? intval($data['status']) : array();
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

    public function getStatus() {
      return $this->data['status'];
    }

    public function isClaimed() {
      return $this->data['status'] != self::STATUS_UNCLAIMED;
    }

    public function setId(MongoId $id) {
      $this->data['_id'] = $id;
    }
  }
?>