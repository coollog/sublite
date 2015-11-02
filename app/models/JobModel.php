<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface JobModelInterface {
    public function __construct();

    /**
     * Gets the 'application' field of a job document.
     * Returns null if the job does not exist or does not have an 'application'
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
    public static function getByIdMinimal(MongoId $jobId);
  }

  class JobModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public static function getApplicationQuestionIds(MongoId $jobId) {
      $questionIds = (new DBQuery(self::$collection))
        ->toQuery('_id', $jobId)->projectField('application')->findOne();
      return isset($questionIds['application']) ?
        $questionIds['application']['questions'] : null;
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
      return self::getById($jobId, ['title' => true, 'location' => true]);
    }

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'jobs');
    }

    function save($data, $setRecruiter=true) {
      if ($setRecruiter) $data['recruiter'] = $_SESSION['_id'];
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function find($query=array()) {
      return self::$collection->find($query);
    }
    function last($n=1) {
      return self::$collection->find()->sort(array('_id'=>-1))->limit($n);
    }

    function owner($id) {
      if (is_null($entry = $this->get($id))) return NULL;
      return $entry['recruiter'];
    }

    function delete($id) {

    }

    function exists($id) {
      return (self::get($id) !== NULL);
    }

    function incrementApply($id) {
      $entry = $this->get($id);
      if($entry !== NULL) {
        ++$entry['stats']['clicks'];
        if(isset($_SESSION['loggedinstudent'])) {
          $entry['applicants'][] = array($_SESSION['_id'], new MongoDate());
        }
        else {
          $entry['applicants'][] = array('', new MongoDate());
        }
        $this->save($entry, false);
        return $entry['stats']['clicks'];
      }
      else {
        return NULL;
      }
    }

    protected static $collection;
  }

  GLOBALvarSet('MJob', new JobModel());
?>