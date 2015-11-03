<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface RecruiterModelInterface {
    public static function getCredits(MongoId $recruiterId);
    public static function setCredits(MongoId $recruiterId, $count);
    public static function addCredits(MongoId $recruiterId, $count);
    public static function subtractCredits(MongoId $recruiterId, $count);

    // Payment stuff.
    /**
     * @return null if no customerId.
     */
    public static function getCustomerId(MongoId $recruiterId);
    /**
     * Sets the customer id.
     */
    public static function setCustomerId(MongoId $recruiterId, $customerId);
  }

  class RecruiterModel extends Model implements RecruiterModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public static function getCredits(MongoId $recruiterId) {
      $query = (new DBQuery(self::$collection))
        ->queryForId($recruiterId)->projectField('credits');
      $recruiter = $query->findOne();
      return $recruiter['credits'];
    }

    public static function setCredits(MongoId $recruiterId, $count) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)->toUpdate('credits', intval($count));
      $update->run();
    }

    public static function addCredits(MongoId $recruiterId, $count) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)->toAdd('credits', intval($count));
      $update->run();
    }

    public static function subtractCredits(MongoId $recruiterId, $count) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)->toAdd('credits', -$count);
      $update->run();
    }

    public static function getCustomerId(MongoId $recruiterId) {
      $entry = self::getById($recruiterId, ['paymentinfo' => 1]);
      if (!isset($entry['paymentinfo']['customerid'])) return null;
      return $entry['paymentinfo']['customerid'];
    }

    public static function setCustomerId(MongoId $recruiterId, $customerId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)
        ->toUpdate('paymentinfo.customerid', $customerId);
      $update->run();
    }

    function __construct() {
      parent::__construct(self::DB_TYPE, 'recruiters');
    }

    function save($data) {
      $data['msgs'] = array();
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function login($email, $pass) {
      if (is_null($entry = $this->get($email))) return false;
      return hash_equals($entry['pass'], crypt($pass, $entry['pass']));
    }

    function get($email) {
      return self::$collection->findOne(array('email' => $email));
    }
    function getByPass($pass) {
      return self::$collection->findOne(array('pass' => $pass));
    }
    function getCompany($rid) {
      $r = self::getByID($rid);
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