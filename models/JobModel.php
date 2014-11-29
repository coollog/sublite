<?php
  require_once('models/Model.php');

  class JobModel extends Model {
    function __construct() {
      parent::__construct('jobs');
    }

    function save($data) {
      $data['applicants'] = array();
      $data['recruiter'] = $_SESSION['_id'];
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
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

    function data($entry) {
      $title = $entry['title'];
      $deadline = $entry['deadline'];
      $duration = $entry['duration'];
      $desc = $entry['desc'];
      $funfacts = $entry['funfacts'];
      $photo = $entry['photo'];
      $location = $entry['location'];
      $requirements = $entry['requirements'];
      $data = array(
        'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
        'desc' => $desc, 'funfacts' => $funfacts, 'photo' => $photo,
        'location' => $location, 'requirements' => $requirements
      );
      return $data;
    }
  }

  $MJob = new JobModel();

?>