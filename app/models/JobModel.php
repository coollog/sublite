<?php
  interface JobModelInterface {
    public function __construct();

    /**
     * Gets the 'application' field of a job document.
     * Returns [] if the job does not exist or does not have an 'application'
     * field.
     */
    public static function getApplicationQuestionIds(MongoId $jobId);

    /**
     * Sets the 'application' field of a job document.
     */
    public static function setApplicationQuestionIds(MongoId $jobId,
                                                     array $questionIds);

    /**
     * Checks if job has $jobId and $recruiterId.
     */
    public static function matchJobRecruiter(MongoId $jobId,
                                             MongoId $recruiterId);

    public static function getRecruiterId(MongoId $jobId);
    public static function getByRecruiter(MongoId $recruiterId);

    /**
     * @return Gets 'title', 'location', 'company', 'recruiter'.
     */
    public static function getByIdMinimal(MongoId $jobId);

    public static function save(array $data, $setRecruiter);
    public static function owner(MongoId $jobId);
    public static function incrementApply(MongoId $jobId);
  }

  class JobModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'jobs');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(['recruiter' => 1]));
    }

    public static function getApplicationQuestionIds(MongoId $jobId) {
      $questionIds = (new DBQuery(self::$collection))
        ->toQuery('_id', $jobId)->projectField('application')->findOne();
      return isset($questionIds['application']) ?
        $questionIds['application']['questions'] : [];
    }

    public static function setApplicationQuestionIds(MongoId $jobId,
                                                     array $questionIds) {
      invariant(isset(self::$collection));
      $update = (new DBUpdateQuery(self::$collection))
        ->toQuery('_id', $jobId)->toUpdate('application', $questionIds);
      $update->run();
    }

    public static function matchJobRecruiter(MongoId $jobId,
                                             MongoId $recruiterId) {
      $query = self::queryForId($jobId)
        ->toQuery('recruiter', $recruiterId)->projectId();
      return !is_null($query->findOne());
    }

    public static function getRecruiterId(MongoId $jobId) {
      $query = self::queryForId($jobId)->projectField('recruiter');
      $job = $query->findOne();
      return $job['recruiter'];
    }

    public static function getByRecruiter(MongoId $recruiterId,
                                          array $projection = array()) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('recruiter', $recruiterId);
      return $query->run();
    }

    public static function getByIdMinimal(MongoId $jobId) {
      return self::getById($jobId, [
        'title' => 1,
        'location' => 1,
        'company' => 1,
        'recruiter' => 1
      ]);
    }

    public static function save(array $data, $setRecruiter=true) {
      if ($setRecruiter) {
        $data['recruiter'] = $_SESSION['_id'];
      }
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function owner(MongoId $jobId) {
      $job = self::getById($jobId, ['recruiter' => 1]);
      if (is_null($job)) return NULL;
      return $job['recruiter'];
    }

    public static function incrementApply(MongoId $jobId) {
      $job = self::getById($jobId);
      if (is_null($job)) return null;

      ++ $job['stats']['clicks'];
      if (isset($_SESSION['loggedinstudent'])) {
        $job['applicants'][] = array($_SESSION['_id'], new MongoDate());
      }
      else {
        $job['applicants'][] = array('', new MongoDate());
      }
      self::save($job, false);
      return $job['stats']['clicks'];
    }

    protected static $collection;
  }

  new JobModel();
?>