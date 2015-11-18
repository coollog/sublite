<?php
  interface RecruiterModelInterface {
    public function __construct();

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

    public static function save(array $data);
    public static function login($email, $pass);
    public static function getByEmail($email);
    public static function getByPass($pass);
    public static function getCompany(MongoId $recruiterId);
    public static function getName(MongoId $recruiterId);
    public static function getEmail(MongoId $recruiterId);
    public static function getPhoto(MongoId $recruiterId);
    public static function me();
    public static function emailExists($email);
  }

  class RecruiterModel extends Model implements RecruiterModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    const CREDITS_FOR_COMPANYPROFILE = 2;
    const CREDITS_FOR_JOB            = 1;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'recruiters');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(['email' => 1]));
      mongo_ok(self::$collection->createIndex(['pass' => 1]));
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

    public static function save(array $data) {
      $data['msgs'] = array();
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function login($email, $pass) {
      if (is_null($recruiter = self::getByEmail($email)))
        return false;
      return hash_equals($recruiter['pass'], crypt($pass, $recruiter['pass']));
    }

    public static function getByEmail($email) {
      $query = (new DBQuery(self::$collection))->toQuery('email', $email);
      return $query->findOne();
    }
    public static function getByPass($pass) {
      $query = (new DBQuery(self::$collection))->toQuery('pass', $pass);
      return $query->findOne();
    }
    public static function getCompany(MongoId $recruiterId) {
      $recruiter = self::getById($recruiterId, ['company' => 1]);
      return CompanyModel::getById($recruiter['company']);
    }
    public static function getName(MongoId $recruiterId) {
      $recruiter = self::getById($recruiterId, [
        'firstname' => 1,
        'lastname' => 1
      ]);
      return "$recruiter[firstname] $recruiter[lastname]";
    }
    public static function getEmail(MongoId $recruiterId) {
      $recruiter = self::getById($recruiterId, ['email' => 1]);
      return $recruiter['email'];
    }
    public static function getPhoto(MongoId $recruiterId) {
      $recruiter = self::getById($recruiterId, ['photo' => 1]);
      return isset($recruiter['photo']) ? $recruiter['photo'] : null;
    }
    public static function me() {
      if (!isset($_SESSION['email'])) return null;
      return self::getByEmail($_SESSION['email']);
    }

    public static function emailExists($email) {
      return !is_null($this->getByEmail($email));
    }

    protected static $collection;
  }

  new RecruiterModel();
?>