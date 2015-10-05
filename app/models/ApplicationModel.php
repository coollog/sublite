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
  }

  class ApplicationModel extends Model implements ApplicationModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      self::$collection = parent::__construct(self::DB_TYPE, 'jobs');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(array('jobid' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                      'submitted' => 1)));
    }

    public static function markAsSubmitted($applicationId) {
      $update = (new DBUpdate(self::$collection))
        ->toQuery('_id', $application['_id'])
        ->toUpdate('submitted', true);
      $update->run();
    }

    public static function setJobApplication(MongoId $jobId, array $data) {

    }

    public static function getSavedForJob(MongoId $jobId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobId', $jobId)->toQuery('submitted', false);
      return $query->run();
    }

    public static function replaceQuestionsField(MongoId $applicationId,
                                                 array $newQuestions) {
      $update = (new DBUpdate(self::$collection))
        ->toQuery('_id', $applicationId)
        ->toUpdate('questions', $newQuestions);
      $update->run();
    }

    private static $collection;
  }
?>