<?php
  interface ApplicationModelInterface {
    public function __construct();

    /**
     * For $applicationId, sets 'submitted' to true, and 'profile' to
     * '$studentProfile'.
     */
    public static function submitWithStudentProfile(
      MongoId $applicationId, StudentProfile $studentProfile);

    /**
     * Sets 'submitted' for $applicationId to true.
     */
    public static function markAsSubmitted(MongoId $applicationId);

    /**
     * Checks whether $applicationId is marked as submitted.
     */
    public static function checkApplicationSubmitted(MongoId $applicationId);

    /**
     * Set the job document's application field to $data.
     */
    public static function setJobApplication(MongoId $jobId, array $data);

    /**
     * Get the job document's application field.
     */
    public static function getJobApplication(MongoId $jobId);

    public static function hasJobApplication(MongoId $jobId);

    /**
     * Retrieves all the saved applications for $jobId.
     */
    public static function getSavedForJob(MongoId $jobId);

    /**
     * Replace the questions field for $applicationId.
     */
    public static function replaceQuestionsField(MongoId $applicationId,
                                                 array $newQuestions);

    /**
     * Unclaimed means 'status' is unclaimed and 'submitted' is true.
     */
    public static function getUnclaimed(MongoId $jobId);
    public static function getClaimed(MongoId $jobId);

    /**
     * Checks whether the student has submitted or saved an application for the
     * job.
     */
    public static function applicationExists(MongoId $jobId,
                                             MongoId $studentId);

    /**
     * Gets application given a jobId and studentId
     */
    public static function getApplication(MongoId $jobId, MongoId $studentId);
  }

  class ApplicationModel extends Model implements ApplicationModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'applications');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(array('jobid' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'submitted' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'status' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'status' => 1,
                                                    'submitted' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'studentid' => 1)));
    }

    public static function submitWithStudentProfile(
      MongoId $applicationId, StudentProfile $studentProfile) {
      $update = (new DBUpdateQuery(self::$collection))
        ->toQuery('_id', $applicationId)
        ->toUpdate('submitted', true)
        ->toUpdate('profile', $studentProfile->getData());
      $update->run();
    }

    public static function markAsSubmitted(MongoId $applicationId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->toQuery('_id', $applicationId)
        ->toUpdate('submitted', true);
      $update->run();
    }

    public static function markAsUnSubmitted(MongoId $applicationId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->toQuery('_id', $applicationId)
        ->toUpdate('submitted', false);
      $update->run();
    }

    public static function checkApplicationSubmitted(MongoId $applicationId) {
      $query = (new DBQuery(self::$collection))->toQuery('_id', $applicationId);
      return $query->findOne()['submitted'];
    }

    public static function setJobApplication(MongoId $jobId, array $data) {
      // Check job exists.
      if (!self::jobExists($jobId)) return false;

      JobModel::setApplicationQuestionIds($jobId, $data);
      return true;
    }

    public static function getJobApplication(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      return JobModel::getApplicationQuestionIds($jobId);
    }

    public static function hasJobApplication(MongoId $jobId) {
      return self::getJobApplication($jobId) !== null;
    }

    public static function getSavedForJob(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)->toQuery('submitted', false);
      return $query->run();
    }

    public static function replaceQuestionsField(MongoId $applicationId,
                                                 array $newQuestions) {
      $update = (new DBUpdateQuery(self::$collection))
        ->toQuery('_id', $applicationId)
        ->toUpdate('questions', $newQuestions);
      $update->run();
    }

    public static function getUnclaimed(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('status', ApplicationStudent::STATUS_UNCLAIMED)
        ->toQuery('submitted', true);
      return $query->run();
    }

    public static function getClaimed(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toNotQuery('status', ApplicationStudent::STATUS_UNCLAIMED);
      return $query->run();
    }

    public static function applicationExists(MongoId $jobId,
                                             MongoId $studentId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('studentid', $studentId)
        ->projectId();
      return $query->findOne() !== null;
    }

    public static function getApplication(MongoId $jobId, MongoId $studentId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('studentid', $studentId)
        ->projectId();
      return $query->findOne();
    }

    private static function jobExists(MongoId $id) {
      return JobModel::exists($id);
    }

    protected static $collection;
  }

  new ApplicationModel();
?>