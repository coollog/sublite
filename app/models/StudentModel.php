<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class StudentModel extends Model {
    function __construct($test=false) {
      parent::__construct(parent::DB_STUDENTS, 'emails', $test);
    }

    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function find($query) {
      return $this->collection->find($query);
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
      $student = $this->collection->findOne(array('_id' => new MongoId($id)));
      $student['photo'] = isset($student['photo']) ? $student['photo'] :
        $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
      return $student;
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
      return isset($entry['photo']) ? $entry['photo'] :
        $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
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