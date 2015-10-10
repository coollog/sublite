<?php
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Application.php');

  interface ApplicationJobInterface {
    /**
     * Creates an application as the 'application' field in a job document.
     */
    public static function createOrUpdate(MongoId $jobId, array $questions);

    public function __construct(MongoId $job, array $data);
    public function getJobId();

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

    // The _id of the job associated with the application.
    private $jobId;
  }
?>