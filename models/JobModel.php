<?php
  require_once('models/Model.php');

  class JobModel extends Model {
    function __construct() {
      parent::__construct('jobs');
    }

    function save($id, $data) {

    }

    function get($id) {

    }

    function delete($id) {
      
    }
    
    function exists($id) {

    }
  }

  $MJob = new JobModel();

?>