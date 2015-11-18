<?php
  interface StudentModelInterface {
    public function __construct();

    public static function getAnswers(MongoId $studentId);
    public static function replaceAnswers(MongoId $studentId, array $answers);
    public static function getProfile(MongoId $studentId);
    public static function setProfile(MongoId $studentId, array $profileData);

    /**
     * Retrieves just email, name, and school name.
     */
    public static function getByIdMinimal(MongoId $studentId);

    public static function save(array $data);
    public static function find(array $query);
    public static function getByEmail($email);
    public static function getAllwTime();
    public static function getIdByEmail($email);
    public static function getName(MongoId $studentId);
    public static function getPhoto(MongoId $studentId);
    public static function getEmail(MongoId $studentId);
    public static function me();
    public static function login($email, $pass);
    public static function emailExists($email);
  }

  class StudentModel extends Model {
    const DB_TYPE = parent::DB_STUDENTS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'emails');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(['email' => 1]));
      mongo_ok(self::$collection->createIndex(['time' => 1]));
    }

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

    public static function save(array $data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function find(array $query) {
      return self::$collection->find($query);
    }
    public static function getByEmail($email) {
      $query = (new DBQuery(self::$collection))->toQuery('email', $email);
      return $query->findOne();
    }
    public static function getAllwTime() {
      $query = (new DBQuery(self::$collection))
        ->toQuery('time', ['$exists' => true]);
      return $query->run();
    }
    public static function getById(MongoId $studentId) {
      $student = parent::getById($studentId);
      if (Is_null($student)) return null;
      $student['photo'] = isset($student['photo']) ? $student['photo'] :
        $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
      return $student;
    }
    public static function getIdByEmail($email) {
      $student = self::getByEmail($email);
      if (is_null($student)) return null;
      return $student['_id'];
    }
    public static function getName(MongoId $studentId) {
      $student = self::getById($studentId, ['name' => 1]);
      return $student['name'];
    }
    public static function getPhoto(MongoId $studentId) {
      $student = self::getById($studentId, ['photo' => 1]);
      return isset($student['photo']) ? $student['photo'] :
        $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
    }
    public static function getEmail(MongoId $studentId) {
      $student = self::getById($studentId, ['email' => 1]);
      return $student['email'];
    }
    public static function me() {
      if (isset($_SESSION['loggedinstudent']))
        return self::getByEmail($_SESSION['email']);

      return [
        'email' => '',
        'name' => '',
        'gender' => ''
      ];
    }

    public static function login($email, $pass) {
      $student = self::getByEmail($email);
      if (is_null($student)) return false;

      return $student['pass'] == md5($pass);
    }

    public static function emailExists($email) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('email', $email)->projectId();
      return !is_null($query->findOne());
    }

    protected static $collection;
  }

  new StudentModel();
?>