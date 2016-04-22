<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface RecruiterModelInterface {
    public static function getNumUnread(MongoId $recruiterId);
    public static function setUnread(MongoId $recruiterId, $count);
    public static function incrementUnread(MongoId $recruiterId);
    public static function decrementUnread(MongoId $recruiterId);

    public static function getCredits(MongoId $recruiterId);
    public static function setCredits(MongoId $recruiterId, $count);
    public static function addCredits(MongoId $recruiterId, $count);
    public static function subtractCredits(MongoId $recruiterId, $count);

    public static function addCreditsForNewCompanyProfile(MongoId $recruiterId);
    public static function addCreditsForNewJob(MongoId $recruiterId);

    // Payment stuff.
    /**
     * @return null if no customerId.
     */
    public static function getCustomerId(MongoId $recruiterId);

    /**
     * Sets the customer id.
     */
    public static function setCustomerId(MongoId $recruiterId, $customerId);

    public static function getFirstname(MongoId $recruiterId);

    /**
     * Gets just the 'email', 'firstname', 'lastname'.
     */
    public static function getByIdMinimal(MongoId $recruiterId);
    public static function getEmail(MongoId $recruiterId);

    // Gets the entry for the current user.
    public static function me();

    // Save the entry after changes or create it.
    public static function save(array $data);

    // @return if current user has a company.
    public static function hasCompany();
  }

  class RecruiterModel extends Model implements RecruiterModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    const CREDITS_FOR_COMPANYPROFILE = 1;
    const CREDITS_FOR_JOB            = 1;

    public static function init() {
      parent::init(self::DB_TYPE, 'recruiters');
      parent::checkReady();
    }

    public static function getNumUnread(MongoId $recruiterId) {
      $query = (new DBQuery(self::$collection))
        ->queryForId($recruiterId)->projectField('unread');
      $recruiter = $query->findOne();
      return $recruiter['unread'];
    }

    public static function setUnread(MongoId $recruiterId, $count) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)->toUpdate('unread', $count);
      $update->run();
    }

    public static function incrementUnread(MongoId $recruiterId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)->toAdd('unread', 1);
      $update->run();
    }

    public static function decrementUnread(MongoId $recruiterId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($recruiterId)->toAdd('unread', -1);
      $update->run();
    }

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

    public static function addCreditsForNewCompanyProfile(
      MongoId $recruiterId) {
      self::addCredits($recruiterId, self::CREDITS_FOR_COMPANYPROFILE);
    }

    public static function addCreditsForNewJob(
      MongoId $recruiterId) {
      self::addCredits($recruiterId, self::CREDITS_FOR_JOB);
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

    public static function getFirstname(MongoId $recruiterId) {
      $entry = self::getById($recruiterId, ['firstname' => 1]);
      return $entry['firstname'];
    }

    public static function getByIdMinimal(MongoId $recruiterId) {
      return self::getById($recruiterId, [
        'email' => 1,
        'firstname' => 1,
        'lastname' => 1
      ]);
    }

    public static function getEmail(MongoId $id) {
      $entry = self::getById($id);
      return $entry['email'];
    }

    public static function me() {
      if (!isset($_SESSION['email'])) return null;
      return self::get($_SESSION['email']);
    }

    public static function login($email, $pass) {
      if (is_null($entry = self::get($email))) return false;
      // Matches old hash
      // password_get_info algoName is 'unknown' if (old) passwords are
      // encrypted with crypt() and 'bcrypt' if encrypted with password_hash()
      // (new)
      if (password_get_info($entry['pass'])['algoName'] == 'unknown'
          && hash_equals($entry['pass'], crypt($pass, $entry['pass']))) {
        $entry['pass'] = password_hash($pass, PASSWORD_DEFAULT);
        self::save($entry);
        return true;
      }

      return password_verify($pass, $entry['pass']);
    }

    public static function save(array $data) {
      $data['msgs'] = [];
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function hasCompany() {
      $me = self::me();
      return MongoId::isValid($me['company']);
    }

    public static function getByPass($pass) {
      return (new DBQuery(self::$collection))
        ->toQuery('pass', $pass)->findOne();
    }

    function __construct() {}

    function get($email) {
      return self::$collection->findOne([ 'email' => $email ]);
    }

    function getCompany($rid) {
      $r = self::getByID($rid);
      global $MCompany;
      return $MCompany->get($r['company']);
    }

    function getName($id) {
      $entry = self::getByIdOnCollection(self::$collection, new MongoId($id));
      return "$entry[firstname] $entry[lastname]";
    }

    function getPhoto($id) {
      $entry = $this->getById(new MongoId($id));
      return isset($entry['photo']) ? $entry['photo'] : null;
    }

    function find($query=array()) {
      return self::$collection->find($query);
    }

    function exists($email) {
      return (self::get($email) !== NULL);
    }

    function IDexists($id) {
      return self::$collection->findOne([ '_id' => new MongoId($id) ]) !== NULL;
    }

    protected static $collection;
  }

  GLOBALvarSet('MRecruiter', new RecruiterModel());
  RecruiterModel::init();
?>
