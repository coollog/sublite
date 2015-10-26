<?php
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Application.php');

  interface ApplicationJobInterface {
    /**
     * Creates an application as the 'application' field in a job document.
     */
    public static function createOrUpdate(MongoId $jobId, array $questions);

    /**
     * Retrieves the application for $jobId.
     */
    public static function get(MongoId $jobId);

    /**
     * Checks application existence.
     */
    public static function exists(MongoId $jobId);

    public function __construct(MongoId $job, array $data);
    public function getJobId();
    public function getQuestions();

    // $data is an associative array containing a subset of these keys:
    // - array of questions (optional) - saved as list of question ids
  }

  class ApplicationJob extends Application implements ApplicationJobInterface {
    public static function createOrUpdate(MongoId $jobId, array $questions) {
      $application = new ApplicationJob($jobId, [
        'questions' => $questions,
      ]);
      $setSuccess =
        ApplicationModel::setJobApplication($jobId, $application->getData());

      if (!$setSuccess) return false;

      // Update student applications saved for this job to be new list of
      // questions.
      self::updateSavedApplications($jobId, $application->getData());

      return true;
    }

    public static function get(MongoId $jobId) {
      $applicationData = ApplicationModel::getJobApplication($jobId);
      if (is_null($applicationData)) {
        return null;
      }

      return new ApplicationJob($jobId, $applicationData);
    }

    public static function exists(MongoId $jobId) {
      $applicationData = ApplicationModel::getJobApplication($jobId);
      return !is_null($applicationData);
    }

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
        isset($data['questions']) ? $data['questions'] : [];
    }

    public function getJobId() {
      return $this->jobId;
    }

    public function getQuestions() {
      return $this->data['questions'];
    }

    // The _id of the job associated with the application.
    private $jobId;
  }
?>