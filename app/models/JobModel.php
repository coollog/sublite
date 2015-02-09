<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class JobModel extends Model {
    function __construct() {
      parent::__construct('jobs');
    }

    function save($data, $setRecruiter=true) {
      if ($setRecruiter) $data['recruiter'] = $_SESSION['_id'];
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function getByRecruiter($id) {
      return $this->collection->find(array('recruiter' => new MongoId($id)));
    }
    function getAll() {
      return $this->collection->find();
    }
    function find($query=array()) {
      return $this->collection->find($query);
    }
    function last($n=1) {
      return $this->collection->find()->sort(array('_id'=>-1))->limit($n);
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