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
    function getAll() {
      return $this->collection->find();
    }
    function getAllwTime() {
      return $this->collection->find(array('time' => array('$exists' => true)));
    }
    function getById($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function getID($email) {
      if (is_null($entry = $this->get($email))) return $entry;
      else return $entry['_id'];
    }
    function getName($id) {
      $entry = $this->getById($id);
      return $entry['name'];
    }
    function getPhoto($id) {
      $entry = $this->getById($id);
      return isset($entry['photo']) ? $entry['photo'] : null;
    }
    function getEmail($id) {
      $entry = $this->getById($id);
      return $entry['email'];
    }
    function last($n=1) {
      return $this->collection->find()->sort(array('_id'=>-1))->limit($n);
    }
    function me() {
      if (isset($_SESSION['loggedinstudent']))
        return $this->get($_SESSION['email']);
      else {
        return array(
          'email' => '',
          'name' => '',
          'gender' => ''
        );
      }
        
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
    function existsEmail($email) {
      return ($this->collection->findOne(array('email' => $email)) !== NULL);
    }
  }

  $MStudent = new StudentModel();

?>