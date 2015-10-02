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
     * Set the job document's application field to $data.
     */
    public static function setJobApplication(MongoId $jobId, array $data) {

    }

    public static function updateSavedApplications(MongoId $jobId,
                                                   array $questionIds) {
      // Get the saved applications corresponding to $jobId.
      $query = (new DBQuery(static::$collection))
        ->toQuery('jobId', $jobId)->toQuery('submitted', false);
      $saved = $query->run();

      // Prune the questions field to be just those in $questionIds.
      foreach ($saved as $application) {
        $questions = $application['questions'];

        $questionIdHash = arrayToHashByKey($questions, '_id');

        $newQuestions = array();

        foreach ($questionIds as $questionId) {
          if (isset($questionIdHash[$questionsId])) {
            $newQuestions[] = $questionIdHash[$questionId];
          } else {
            $newQuestions[] = array('_id' => $questionId, 'answer' => '');
          }
        }

        // Update the entry with $newQuestions.
        $update = (new DBUpdate(static::$collection))
          ->toQuery('_id', $application['_id'])
          ->toUpdate('questions', $newQuestions);
        $update->run();
      }

    }
  }
?>