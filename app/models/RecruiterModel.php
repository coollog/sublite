<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class RecruiterModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct() {
      parent::__construct(self::DB_TYPE, 'recruiters');
    }

    function save($data) {
      $data['msgs'] = array();
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function login($email, $pass) {
      if (($entry = $this->get($email)) === NULL) return false;
      return hash_equals($entry['pass'], crypt($pass, $entry['pass']));
    }

    function get($email) {
      return self::$collection->findOne(array('email' => $email));
    }
    function getByPass($pass) {
      return self::$collection->findOne(array('pass' => $pass));
    }
    function getCompany($rid) {
      $r = $this->getByID($rid);
      global $MCompany;
      return $MCompany->get($r['company']);
    }
    function getName($id) {
      $entry = $this->getById(new MongoId($id));
      return $entry['firstname'] . ' ' . $entry['lastname'];
    }
    function getEmail($id) {
      $entry = $this->getById($id);
      return $entry['email'];
    }
    function getPhoto($id) {
      $entry = $this->getById(new MongoId($id));
      return isset($entry['photo']) ? $entry['photo'] : null;
    }
    function me() {
      if (!isset($_SESSION['email'])) return null;
      return $this->get($_SESSION['email']);
    }
    function find($query=array()) {
      return self::$collection->find($query);
    }

    function exists($email) {
      return ($this->get($email) !== NULL);
    }
    function IDexists($id) {
      return (self::$collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }

    protected static $collection;
  }

  GLOBALvarSet('MRecruiter', new RecruiterModel());
?>