<?php
  interface ApplicationModelInterface {
    public function __construct();

    /**
     * Sets 'submitted' for $appliationId to true.
     */
    public static function markAsSubmitted($applicationId);

    /**
     * Set the job document's application field to $data.
     */
    public static function setJobApplication(MongoId $jobId, array $data);

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
    }

    public static function markAsSubmitted($applicationId) {
      $update = (new DBUpdate(self::$collection))
        ->toQuery('_id', $application['_id'])
        ->toUpdate('submitted', true);
      $update->run();
    }

    public static function setJobApplication(MongoId $jobId, array $data) {
      // Check job exists.
      if (!self::jobExists($jobId)) return false;

      JobModel::setApplicationQuestionIds($jobId, $data);
      return true;
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
      $update = (new DBUpdate(self::$collection))
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

    private static function jobExists(MongoId $id) {
      return JobModel::exists($id);
    }

    protected static $collection;
  }
?>