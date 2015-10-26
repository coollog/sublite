<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface StudentModelInterface {
    public function __construct();
    public static function getAnswers(MongoId $studentId);
    public static function replaceAnswers(MongoId $studentId, array $answers);
    public static function getProfile(MongoId $studentId);
    public static function setProfile(MongoId $studentId, array $profileData);
  }

  class StudentModel extends Model {
    const DB_TYPE = parent::DB_STUDENTS;

    public static function getAnswers(MongoId $studentId) {
      $query = self::queryForId($studentId)->projectField('answers');
      $studentAnswers = $query->run();
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
    function getAll() {
      return self::$collection->find();
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
      $entry = $this->getById($id);
      return $entry['name'];
    }
    function getPhoto($id) {
      $entry = $this->getById($id);
      return isset($entry['photo']) ? $entry['photo'] :
        $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
    }
    function getEmail($id) {
      $entry = $this->getById($id);
      return $entry['email'];
    }
    function last($n=1) {
      return self::$collection->find()->sort(array('_id'=>-1))->limit($n);
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