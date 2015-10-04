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
    /**
     * Builds a new set of question-answer pairs by taking $questions and using
     * only questions with id in $questionIds. Questions in $questionIds that
     * are not in $questions have an empty answer.
     */
    protected static function pruneQuestionsByIdSet($questions, $questionIds) {
      $questionSet = array();

      // Create a hash from _id to the _id-answer pair.
      $questionsHash = arrayToHashByKey($questions, '_id');

      // For all the ids in $questionIds, add an _id-answer pair to questionSet.
      foreach ($questionIds as $questionId) {
        if (isset($questionsHash[$questionId]) {
          $questionSet[] = $questionsHash[$questionId];
        } else {
          $questionSet[] = array('_id' => $questionId, 'answer' => '');
        }
      }

      return $questionSet;
    }

    //**********************
    // non-static functions
    //**********************

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
      self::updateSavedApplications($jobId, $application->getData());

      return true;
    }

    // public static function updateQuestions(MongoId $recruiter,
    //                                        MongoId $application,
    //                                        array $questions) {
    //   if (ApplicationModel::getById($application)->getRecruiter != $recruiter) {
    //     // TODO Fail
    //   }
    //   $application->setQuestions($questions);
    //   // TODO Finish
    // }

    /**
     * Updates saved student applications to be the new set of $questionsIds.
     */
    private static function updateSavedApplications(MongoId $jobId,
                                                   array $questionIds) {
      // Get the saved applications corresponding to $jobId.
      $saved = ApplicationModel::getSavedForJob($jobId);

      // Prune the questions field to be just those in $questionIds.
      foreach ($saved as $application) {
        $questions = $application['questions'];

        $newQuestions = self::pruneQuestionsByIdSet($questions, $questionIds);

        // Update the entry with $newQuestions.
        ApplicationModel::replaceQuestionsField($application['_id'], $newQuestions);
      }

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
     * Student saves application, so create it as a new saved application.
     * Need to make sure student has not already saved an application for the
     * job.
     */
    public static function save(MongoId $jobId,
                                MongoId $studentId,
                                array $questions) {
      return self::create($jobId, $studentId, $questions, false);
    }

    /**
     * Student edits a saved application, so just update the question answers.
     * Make sure the student has permission, and that the application is not
     * already submitted.
     */
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

    /**
     * Student submits saved application, so mark it as submitted.
     * Make sure the student has permission.
     */
    public static function submitSaved(MongoId $applicationId) {
      // Mark the application as submitted.
      ApplicationModel::markAsSubmitted($applicationId);

      $applicationData = ApplicationModel::getById($applicationId);
      $application = new ApplicationStudent($applicationData);

      self::saveStudentAnswers($application);
    }

    /**
     * Student submits a new application (not already saved), so create a
     * submitted application.
     */
    public static function submitNew() {
      $application = self::create($jobId, $studentId, $questions, false);

      self::saveStudentAnswers($application);

      return $application;
    }

    /**
     * Deletes a saved application.
     * Make sure the student has permission, and that the application is not
     * already submitted.
     */
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

    // - _id (optional)
    // - job (required)
    // - student (required)
    // - array of questions (optional)
    //   - saved as list of question _id-answer pairs
    // - submitted? (required)
    private $data;
  }
?>