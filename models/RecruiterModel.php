<?php
  require_once('models/Model.php');

  class RecruiterModel extends Model {
    function __construct() {
      parent::__construct('recruiters');
    }

    function save($data) {

    }

    function login($data) {

    }

    function get($email) {

    }
    
    function exists($email) {

    }
  }

  $MRecruiter = new RecruiterModel();

?>