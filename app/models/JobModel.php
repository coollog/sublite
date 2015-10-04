<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface JobModelInterface {
    public function __construct();

    /**
     * Gets the 'application' field of a job document. Returns null if the job
     * does not exist or does not have an 'application' field.
     */
    public static function getApplicationQuestionIds(MongoId $jobId);
  }

  class JobModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public static function getApplicationQuestionIds(MongoId $jobId) {
      $questionIds = (new DBQuery(static::$collection))
        ->toQuery('_id', $jobId)->projectField('application')->findOne();

      return $questionIds['application']['questions'];
    }

    public function __construct() {
      static::$collection = parent::__construct(self::DB_TYPE, 'jobs');
    }

    function save($data, $setRecruiter=true) {
      if ($setRecruiter) $data['recruiter'] = $_SESSION['_id'];
      static::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return static::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function getByRecruiter($id) {
      return static::$collection->find(array('recruiter' => new MongoId($id)));
    }
    function getAll() {
      return static::$collection->find();
    }
    function find($query=array()) {
      return static::$collection->find($query);
    }
    function last($n=1) {
      return static::$collection->find()->sort(array('_id'=>-1))->limit($n);
    }

    function owner($id) {
      if (($entry = $this->get($id)) === NULL) return NULL;
      return $entry['recruiter'];
    }

    function delete($id) {

    }

    function exists($id) {
      return ($this->get($id) !== NULL);
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
  }

  $MJob = new JobModel();

?>