<?php
  interface ApplicationModelInterface {
    public function __construct();
    public static function insert(array $data);

    public static function setJobApplication(MongoId $jobId, array $data);
    public static function updateSavedApplications(MongoId $jobId,
                                                   array $questionIds);
  }

  class ApplicationModel extends Model implements ApplicationModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      static::$collection = parent::__construct(self::DB_TYPE, 'jobs');

      // Create necessary indices.
      mongo_ok(static::$collection->createIndex(array('jobid' => 1)));
      mongo_ok(static::$collection->createIndex(array('jobid' => 1,
                                                      'submitted' => 1)));
    }

    /**
     * Sets 'submitted' for $appliationId to true.
     */
    public static function markAsSubmitted($applicationId) {
      $update = (new DBUpdate(static::$collection))
        ->toQuery('_id', $application['_id'])
        ->toUpdate('submitted', true);
      $update->run();
    }

    /**
     * Set the job document's application field to $data.
     */
    public static function setJobApplication(MongoId $jobId, array $data) {

    }

    public static function getSavedForJob(MongoId $jobId) {
      $query = (new DBQuery(static::$collection))
        ->toQuery('jobId', $jobId)->toQuery('submitted', false);
      return $query->run();
    }

    public static function replaceQuestionsField(MongoId $applicationId,
                                                 array $newQuestions) {
      $update = (new DBUpdate(static::$collection))
        ->toQuery('_id', $applicationId)
        ->toUpdate('questions', $newQuestions);
      $update->run();
    }
?>