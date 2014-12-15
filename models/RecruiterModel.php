<?php
  require_once('models/Model.php');

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
    function me() {
      return $this->get($_SESSION['email']);
    }
    
    function exists($email) {
      return ($this->get($email) !== NULL);
    }
  }

  $MRecruiter = new RecruiterModel();

?>