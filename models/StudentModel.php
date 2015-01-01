<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class StudentModel extends Model {
    function __construct() {
      $m = new MongoClient($GLOBALS['dburistudent']);
      $db = $m->$GLOBALS['dbnamestudent'];
      $this->collection = $db->emails;
    }

    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($email) {
      return $this->collection->findOne(array('email' => $email));
    }
    function getID($email) {
      if (is_null($entry = $this->get($email))) return $entry;
      else return $entry['_id'];
    }
    function me() {
      return $this->get($_SESSION['email']);
    }

    function login($email, $pass) {
      if (($entry = $this->get($email)) === NULL) return false;
      return $entry['pass'] == md5($pass);
    }

    function delete($id) {
      
    }
    
    function exists($id) {
      return ($this->collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }
  }

  $MStudent = new StudentModel();

?>