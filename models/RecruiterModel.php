<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class RecruiterModel extends Model {
    function __construct() {
      parent::__construct('recruiters');
    }

    function save($data) {
      $data['msgs'] = array();
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function login($email, $pass) {
      if (($entry = $this->get($email)) === NULL) return false;
      return hash_equals($entry['pass'], crypt($pass, $entry['pass']));
    }

    function get($email) {
      return $this->collection->findOne(array('email' => $email));
    }
    function getByID($id) {
      return $this->collection->findOne(array('_id' => new MongoID($id)));
    }
    function getByPass($pass) {
      return $this->collection->findOne(array('pass' => $pass));
    }
    function getCompany($rid) {
      $r = $this->getByID($rid);
      global $MCompany;
      return $MCompany->get($r['company']);
    }
    function getName($id) {
      $entry = $this->getById($id);
      return $entry['firstname'] . ' ' . $entry['lastname'];
    }
    function getPic($id) {
      $entry = $this->getById($id);
      return isset($entry['photo']) ? $entry['photo'] : null;
    }
    function me() {
      return $this->get($_SESSION['email']);
    }
    
    function exists($email) {
      return ($this->get($email) !== NULL);
    }
    function IDexists($id) {
      return ($this->collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }
  }

  $MRecruiter = new RecruiterModel();

?>