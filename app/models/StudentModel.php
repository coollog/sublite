<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface StudentModelInterface {
    public function __construct();
    public static function getAnswers(MongoId $studentId);
    public static function replaceAnswers(MongoId $studentId, array $answers);
    public static function getProfile(MongoId $studentId);
    public static function setProfile(MongoId $studentId, array $profileData);

    public static function getNumUnread(MongoId $studentId);
    public static function setUnread(MongoId $studentId, $count);
    public static function incrementUnread(MongoId $studentId);
    public static function decrementUnread(MongoId $studentId);

    /**
     * Retrieves just email, name, and school name.
     */
    public static function getByIdMinimal(MongoId $studentId);
  }

  class StudentModel extends Model {
    const DB_TYPE = parent::DB_STUDENTS;

    public static function getAnswers(MongoId $studentId) {
      $query = self::queryForId($studentId)->projectField('answers');
      $studentAnswers = $query->findOne();
      return isset($studentAnswers['answers']) ? $studentAnswers['answers'] : [];
    }

    public static function replaceAnswers(MongoId $studentId, array $answers) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($studentId)->toUpdate('answers', $answers);
      $update->run();
    }

    public static function getProfile(MongoId $studentId) {
      $query = self::queryForId($studentId)->projectField('profile');
      $studentProfile = $query->findOne();
      if (!isset($studentProfile['profile'])) {
        return null;
      }
      return $studentProfile['profile'];
    }

    public static function setProfile(MongoId $studentId, array $profileData) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($studentId)->toUpdate('profile', $profileData);
      $update->run();
    }

    public static function getByIdMinimal(MongoId $studentId) {
      global $S;
      $student = self::getById($studentId, ['email' => true, 'name' => true]);
      $student['school'] = $S->nameOf($student['email']);
      return $student;
    }

    public static function getNumUnread(MongoId $studentId) {
      $query = (new DBQuery(self::$collection))
        ->queryForId($studentId)->projectField('unread');
      $student = $query->findOne();
      return $student['unread'];
    }

    public static function setUnread(MongoId $studentId, $count) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($studentId)->toUpdate('unread', $count);
      $update->run();
    }

    public static function incrementUnread(MongoId $studentId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($studentId)->toAdd('unread', 1);
      $update->run();
    }

    public static function decrementUnread(MongoId $studentId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($studentId)->toAdd('unread', -1);
      $update->run();
    }

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'emails');
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function find($query) {
      return self::$collection->find($query);
    }
    function get($email) {
      return self::$collection->findOne(array('email' => $email));
    }
    function getAllwTime() {
      return self::$collection->find(array('time' => array('$exists' => true)));
    }
    public static function getById($id) {
      $student = self::$collection->findOne(array('_id' => new MongoId($id)));
      $student['photo'] = isset($student['photo']) ? $student['photo'] :
        $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
      return $student;
    }
    function getID($email) {
      if (is_null($entry = $this->get($email))) return $entry;
      else return $entry['_id'];
    }
    function getName($id) {
      $entry = self::getById($id);
      if (isset($entry['name'])) return $entry['name'];
      else return "No Name";
    }
    function getPhoto($id) {
      $entry = $this->getById($id);
      return isset($entry['photo']) ? $entry['photo'] :
        $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
    }
    function getEmail($id) {
      $entry = self::getById($id, ['email' => 1]);
      return $entry['email'];
    }
    function last($n=1) {
      return self::$collection->find()->sort(array('_id'=>-1))->limit($n);
    }
    function me() {
      if (isset($_SESSION['loggedinstudent']))
        return self::get($_SESSION['email']);
      else {
        return array(
          'email' => '',
          'name' => '',
          'gender' => ''
        );
      }

    }

    function login($email, $pass) {
      if (is_null($entry = $this->get($email))) return false;
      return $entry['pass'] == md5($pass);
    }

    function delete($id) {

    }

    function exists($id) {
      return (self::$collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }
    function existsEmail($email) {
      return (self::$collection->findOne(array('email' => $email)) !== NULL);
    }

    protected static $collection;
  }

  GLOBALvarSet('MStudent', new StudentModel());
?>