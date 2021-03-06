<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/StudentProfile.php');

  interface StudentControllerInterface {
    /**
     * Student dashboard.
     */
    public static function home();

    /**
     * For the career profile.
     */
    public static function editStudentProfile();
    public static function viewStudentProfile();
  }

  class StudentController extends Controller
                          implements StudentControllerInterface {
    public static function home() {
      self::requireLogin();

      $me = self::getMe();

      self::render('student/home', $me);
    }

    public static function editStudentProfile() {
      self::requireLogin();
      global $params;

      $studentId = $_SESSION['_id'];

      if (isset($params['profile'])) {
        // Save the profile.
        extract($params);

        StudentProfile::createOrUpdate($studentId, $profile);
        return;
      }

      $profile = toJSON(self::getStudentProfile($studentId));

      self::render('jobs/student/editprofile', ['profile' => $profile]);
    }

    public static function viewStudentProfile() {
      self::requireLogin();

      $studentId = $_SESSION['_id'];
      $profile = toJSON(self::getStudentProfile($studentId));

      self::render('jobs/student/studentprofile', ['profile' => $profile]);
    }

    private static function getStudentProfile(MongoId $studentId) {
      $name = StudentModel::getName($studentId);

      $profile = StudentProfile::getProfile($studentId)->getData();
      if (is_null($profile)) {
        $profile = [];
      }
      $profile['name'] = $name;

      return $profile;
    }

    private static function getMe() {
      $me = StudentModel::me();
      $me['_id'] = $me['_id']->{'$id'};

      $photo = $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
      if (isset($me['photo']) and !is_null($me['photo'])) {
        $photo = $me['photo'];
        if ($photo == 'nopic.png')
          $photo = $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
      }
      $me['photo'] = $photo;

      if (!isset($me['school']) || strlen($me['school']) == 0) {
        global $S;
        $me['school'] = $S->nameOf($me['email']);
      }

      return $me;
    }

    function data($data) {
      $name = clean($data['name']);
      if (isset($data['pass'])) $pass = $data['pass'];
      if (isset($data['pass2'])) $pass2 = $data['pass2'];
      $gender = isset($data['gender']) ? clean($data['gender']) : '';
      $class = isset($data['class']) ? clean($data['class']) : '';
      $school = isset($data['school']) ? clean($data['school']) : '';
      $bio = isset($data['bio']) ? clean($data['bio']) : '';
      $photo = '';
      $unread = isset($data['unread']) ? $data['unread'] : 0;
      if(isset($data['photo'])) {
        $photo = clean($data['photo']);
      }

      $data = array(
        'gender' => $gender, 'bio' => $bio,
        'name' => $name, 'class' => $class, 'school' => $school,
        'photo' => $photo, 'unread' => $unread
      );
      if (isset($pass) and isset($pass2)) {
        $data['pass'] = $pass;
        $data['pass2'] = $pass2;
      }
      return $data;
    }

    function registrationData($data) {
      $education = isset($data['education']) ? $data['education'] : 'undergraduate';
      $degree;
      $year;
      $graduationMonth;
      $graduationYear;
      if($education == "undergraduate") {
        $degree = $data['undergraduateDegree'];
        $year = $data['undergraduateYear'];
        $graduationMonth = $data['undergraduateGraduationMonth'];
        $graduationYear = $data['undergraduateGraduationYear'];
      } else {
        $degree = $data['graduateDegree'];
        $year = $data['graduateYear'];
        $graduationMonth = $data['graduateGraduationMonth'];
        $graduationYear = $data['graduateGraduationYear'];
      }
      $major = isset($data['majorChooser']) ? $data['majorChooser'] : null;
      $gpa = isset($data['gpaChooser']) ? $data['gpaChooser'] : null;
      $industry = isset($data['industryChooser']) ? array_map("htmlspecialchars_decode", $data['industryChooser']) : array();
      $cities = isset($data['citiesChooser']) ? $data['citiesChooser'] : array();
      if(is_null($industry))
        $industry = array();
      if(is_null($cities))
        $cities = array();
      if(is_null($industry))
        $industry = array();
      $lookingFor = array();
      $internshipTimes = array();
      $fulltimeTimes = array();
      $housingTimes = array();
      $seasons = array('Winter 2016', 'Spring 2016', 'Summer 2016', 'Fall 2016', 'Winter 2017', 'Spring 2017');
      if(isset($data['internship'])) {
        array_push($lookingFor, 'internship');
        $internshipSeasons = array_map(function ($season) {return 'internship' . str_replace(' ', '', $season);}, $seasons);
        for($i = 0; $i < count($seasons); $i++) {
          if(isset($data[$internshipSeasons[$i]]))
            array_push($internshipTimes, $seasons[$i]);
        }
      }
      if(isset($data['fulltime'])) {
        array_push($lookingFor, 'fulltime');
        $fulltimeSeasons = array_map(function ($season) {return 'fulltime' . str_replace(' ', '', $season);}, $seasons);
        for($i = 0; $i < count($seasons); $i++) {
          if(isset($data[$fulltimeSeasons[$i]]))
            array_push($fulltimeTimes, $seasons[$i]);
        }
      }
      if(isset($data['housing'])) {
        array_push($lookingFor, 'housing');
        $housingSeasons = array_map(function ($season) {return 'housing' . str_replace(' ', '', $season);}, $seasons);
        for($i = 0; $i < count($seasons); $i++) {
          if(isset($data[$housingSeasons[$i]]))
            array_push($housingTimes, $seasons[$i]);
        }
      }

      $registration = array('education' => $education, 'degree' => $degree, 'year' => $year,
        'graduationMonth' => $graduationMonth, 'graduationYear' => $graduationYear,
        'major' => $major, 'gpa' => $gpa, 'industry' => $industry, 'cities' => $cities,
        'lookingFor' => $lookingFor, 'internshipTimes' => $internshipTimes,
        'fulltimeTimes' => $fulltimeTimes, 'housingTimes' => $housingTimes);

      return $registration;
    }

    function validateData($data, &$err) {
      $this->validate($this->isValidName($data['firstname']),
        $err, 'first name is too long');
    }

    function index() {
      global $MApp;
      $stats = AppModel::getStats();
      $users = $stats['recruiters'] + $stats['students'];

      $r = isset($_GET['r']) ? $_GET['r'] : null;

      $this->render('student/index', array(
        'users' => number_format($users),
        'jobs' => number_format($stats['jobs']),
        'sublets' => number_format($stats['sublets']),
        'universities' => number_format($stats['universities']),
        'cities' => number_format($stats['cities']),
        'companies' => number_format($stats['companies']),
        'r' => $r
      ));
    }

    function loginRedirectSetup() {
      // Setup after-login redirect
      if (isset($_SERVER['HTTP_REFERER'])) {
        $noredirect = array(
          '',
          '/index.php',
          '/',
          '/register',
          '/login',
          '/jobs/login',
          '/housing/login',
          '/employers/login',
          '/housing/register',
          '/jobs/register',
          '/employers/register'
        );
        $domain = "https://$_SERVER[HTTP_HOST]";
        $thispage = "$domain$_SERVER[REQUEST_URI]";
        $lastpage = $_SERVER['HTTP_REFERER'];
        $lastpagepath = preg_replace("/https*:\/\/$_SERVER[HTTP_HOST]/", '', $lastpage);
        if ($thispage != $lastpage) {
          if (!in_array($lastpagepath, $noredirect)) {
            setcookie('loginredirect', $lastpage, time() + 300);
          } else {
            setcookie('loginredirect', '', time() - 3600);
          }
        }
      }
    }
    function loginRedirect() {
      if (isset($_COOKIE['loginredirect'])) {
        $this->redirectURL($_COOKIE['loginredirect']);
      } else {
        $this->redirect('whereto');
      }
    }

    function login() {
      if (!isset($_GET['whereto'])) $this->loginRedirectSetup();

      if (!isset($_POST['login'])) { $this->render('student/login'); return; }

      global $params, $MStudent;
      // Params to vars
      global $email;
      $email = strtolower(clean($params['email']));
      $pass = $params['pass'];
      $data = array('email' => $email);

      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      $this->validate(($entry = $MStudent->get($email)) != NULL,
        $err, 'not registered<br><small><a href="' . $GLOBALS['dirpre'] . '../employers/login">Are you trying to log in as a recruiter?</a></small>');

      if ($this->isValid()) {
        if (!isset($entry['pass'])) {
          $confirm = $this->sendConfirm($email);
          $entry['confirm'] = $confirm;
          $MStudent->save($entry);

          $err = "Your account has not been confirmed yet. A confirmation email has been sent to <strong>$email</strong>. Check your inbox or spam. The email may take up to 24 hours to show up.";
        } else {
          $this->validate(StudentModel::login($email, $pass),
            $err, 'invalid credentials <br><small><a href="' . $GLOBALS['dirpre'] . '../employers/login">Are you trying to log in as a recruiter?</a></small>');

          if ($this->isValid()) {
            $_SESSION['loggedinstudent'] = true;
            $_SESSION['_id'] = $entry['_id'];
            $_SESSION['email'] = $email;
            $_SESSION['pass'] = $pass;
            $_SESSION['name'] = $entry['name'];

            // $this->redirect('home');
            // $this->redirect('search');
            $this->loginRedirect();

            return;
          }
        }
      }

      $this->error($err);
      $this->render('student/login', $data);
    }

    function register() {
      $this->loginRedirectSetup();

      if (!isset($_POST['register'])) { $this->render('student/register'); return; }

      global $params, $MStudent;
      // Params to vars
      global $email;
      $email = strtolower(clean($params['email']));
      $data = array('email' => $email);

      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      global $S;
      $this->validate($S->verify($email), $err, 'email must be .edu');
      $this->validate(($entry = $MStudent->get($email)) == NULL or !isset($entry['pass']),
        $err, 'email already in use, please log in instead');

      if ($this->isValid()) {
        // Send confirmation email
        $confirm = $this->sendConfirm($email);

        // Set up registration entry
        if ($entry == NULL) {
          $entry = array('email' => $email, 'confirm' => $confirm);
        } else {
          $entry['confirm'] = $confirm;
        }
        $entry['stats'] = array('referrals' => array());
        $entry['time'] = time();
        $entry['unread'] = 0;

        $id = $MStudent->save($entry);

        // Handle referrals
        if (isset($_GET['r']) and $MStudent->exists($r = $_GET['r'])) {
          $referrer = $MStudent->getById($r);
          $referrer['stats']['referrals'][] = $id;
          $MStudent->save($referrer);

          $message = "
            <h1 style=\"padding: 0.5em 0; margin: 1em 0; background: #000; color: #ffd800; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4); text-align: center;\">Thanks for referring your friend!</h1>
            Hi there!
            <br /><br />
            Thanks for referring your friend with email $email! Your friend has also started using SubLite. You have now been entered into our iPad Mini drawing! Good luck!
            <br /><br /><br />
            <i>Thanks again!<br />
            Team SubLite</i>";
          sendgmail($referrer['email'], array("info@sublite.net",
            "SubLite, LLC."), 'SubLite - Successful Referral!', $message);
        }

        $this->render('student/registerfinish', array(
          'id' => $id, 'email' => $email
        ));
        return;
      }

      $this->error($err);
      $this->render('student/register', $data);
    }

    function sendConfirm($email) {

      $id = md5(uniqid($email, true));
      $link = "http://sublite.net/confirm.php?id=$id&email=$email";

      $message = "
        <h1 style=\"padding: 0.5em 0; margin: 1em 0; background: #000; color: #ffd800; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4); text-align: center;\">Welcome to SubLite!</h1>

        SubLite is a subletting and internship platform exclusively for students. We want to ensure a safe and secure experience for you. We also want to make sure you are connected directly with recruiters when searching for jobs. That&rsquo;s why we need you to click on the link below to verify your email address.

        <br /><br />
        <a href='$link'>$link</a>
        <br /><br /><br />
        <i>Thanks again and welcome aboard!<br />
        Team SubLite</i>";

      if (($error = sendgmail($email, array("info@sublite.net", "SubLite, LLC."), 'SubLite Email Confirmation', $message)) !== true) {
        sendgmail('info@sublite.net', 'info@sublite.net', 'Email Confirmation Failed to Send', "Email address: $email<br />Reason: $error");
      }

      return $id;
    }

    function sendReferral() {
      if (isset($_REQUEST['emails']) and
          isset($_REQUEST['name']) and
          isset($_REQUEST['email'])) {
        $emailspre = $_REQUEST['emails'];
        $name = $_REQUEST['name'];
        $r = $_REQUEST['r'];

        // Remove emails that are registered
        global $MStudent;
        $emails = array();
        for ($i = 0; $i < count($emailspre); $i ++) {
          $email = $emailspre[$i];
          if (!$MStudent->existsEmail($email)) {
            $emails[] = $email;
          }
        }

        $message = "
          <h1 style=\"padding: 0.5em 0; margin: 1em 0; background: #000; color: #ffd800; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4); text-align: center;\">SubLite!</h1>

          Hey there!
          <br /><br />
          Check out SubLite, a free website that's helping me find summer opportunities and housing. It was founded by Yale students last year and more than 5,000 students have already started using it.
          <br /><br />
          The link is www.sublite.net?r=$r!
          <br /><br />
          Best,<br />
          $name
        ";

        sendgmail('', array("info@sublite.net", "$name - SubLite, LLC."), 'Your friend has invited you to SubLite!', $message, null, $emails);

        $email = $_REQUEST['email'];
        $emailsstr = implode("<br />\n", $emails);
        $report = "
          $name - $email has referred:
          <br /><br />
          $emailsstr
        ";

        sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'Referral Invitations Sent', $report);
      }
    }

    function confirm() {

      global $params, $MStudent;

      // Validations
      $this->startValidations();
      $this->validate(isset($_REQUEST['id']), $err, 'permission denied');
      $this->validate(isset($_REQUEST['email']), $err, 'permission denied');

      if ($this->isValid()) {
        $confirm = $_REQUEST['id'];
        $email = $_REQUEST['email'];

        $this->validate(($entry = $MStudent->get($email)) != null,
          $err, 'permission denied');
        $this->validate(isset($entry['confirm']) and $entry['confirm'] == $confirm and !isset($entry['pass']),
          $err, 'invalid confirmation code. your code may have expired. return to the registration page to re-enter your email for a new confirmation link.');

        if ($this->isValid()) {

          if (!isset($_POST['register'])) { $this->render('student/confirm'); return; }

          // Params to vars
          extract($data = $this->data($params));
          $registration = self::registrationData($params);
          $data = array_merge($data, $registration);


          $this->validate($pass == $pass2, $err, 'password mismatch');
          $this->validate(strlen($name) > 0, $err, 'name empty');
          $this->validate(!empty($registration['lookingFor']), $err, 'must select at least one checkbox');
          $this->validate(strlen($photo) > 0,
            $err, 'must have profile picture');

          if ($this->isValid()) {
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            // Save new account information
            $entry['name'] = $name;
            $entry['pass'] = $pass;
            // $entry['orig'] = $pass2;
            $entry['class'] = $class;
            $entry['school'] = $school;
            $entry['time'] = time();
            $entry['gender'] = $gender;
            $entry['photo'] = $photo;
            $entry['bio'] = $bio;
            $entry['registration'] = $registration;
            $MStudent->save($entry);

            $params['email'] = $email;
            $_POST['login'] = true; $_GET['whereto'] = true;
              $this->login();
            return;
          }

          $this->error($err);
          $this->render('student/confirm', $data);
          return;
        }
      }

      $this->error($err);
      $this->render('notice');
    }

    function edit() {
      $this->requireLogin();

      global $params, $MStudent;
      $me = $MStudent->me();

      // Validations
      $this->startValidations();

      $registrationInfo = isset($me['registration']) ? $me['registration'] : [];
      if (!isset($_POST['edit'])) { $this->render('student/form', array_merge($this->data($me), $registrationInfo)); return; }

      // Params to vars
      extract($data = $this->data($params));
      $registration = self::registrationData($params);
      $saveData = array_merge($data, array("registration" => $registration));
      $data = array_merge($data, $registration);

      $this->validate(strlen($photo) > 0,
        $err, 'must have profile picture');
      $this->validate(!empty($registration['lookingFor']), $err, 'must select at least one checkbox');

      if ($this->isValid()) {
        $me = array_merge($me, $saveData);
        $MStudent->save($me);

        $this->success('profile saved');
        $this->render('student/form', $data);
        return;
      }

      $this->error($err);
      $this->render('student/form', $data);
    }

    function dataChangePass($data) {
      $pass = $data['pass'];
      $pass2 = $data['pass2'];
      return array(
        'pass' => $pass, 'pass2' => $pass2
      );
    }
    function changePass() {
      global $params, $MStudent;

      // Validations
      $this->startValidations();
      $this->validate(
          isset($_GET['id']) && isset($_GET['code']) &&
          !is_null($entry = $MStudent->getByID($id = new MongoId($_GET['id']))) &&
          $entry['pass'] == $_GET['code'],
        $err, 'permission denied');

      if ($this->isValid()) {
        if (!isset($_POST['change'])) { $this->render('changepass'); return; }

        extract($data = $this->dataChangePass($params));

        $this->validate($pass == $pass2, $err, 'password mismatch');

        if ($this->isValid()) {
          $entry['pass'] = password_hash($pass, PASSWORD_DEFAULT);
          $MStudent->save($entry);

          $params['email'] = $entry['email'];
          $_POST['login'] = true; $this->login();
          return;
        }

        $this->error($err);
        $this->render('changepass', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }

    function dataForgotPass($data) {
      $email = strtolower($data['email']);

      return array(
        'email' => $email
      );
    }
    function forgotPass() {
      global $params, $MStudent;

      if (!isset($_POST['forgot'])) { $this->render('forgotpass'); return; }

      extract($data = $this->dataForgotPass($params));

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MStudent->get($email)) != NULL,
        $err, 'no account found');
      $this->validate(isset($entry['pass']),
        $err, 'account has not been confirmed yet. to resend a confirmation email, <a href="register">register</a> your email address again.');

      if ($this->isValid()) {
        $id = $entry['_id'];
        $name = $entry['name'];
        $pass = $entry['pass'];
        $link = "http://sublite.net/changepass.php?id=$id&code=$pass";

        $msg = "Hi $name!
                <br /><br />
                Below please find the link to reset your password. Thanks for using SubLite!
                <br /><br />
                Change your password here: <a href=\"$link\">$link</a>
                <br /><br />
                If you did not request this password reset, please contact us at <a href=\"mailto:info@sublite.net\">info@sublite.net</a>.
                <br /><br />
                Best,<br />
                The SubLite Team";
        sendgmail($email, array("info@sublite.net",
          "SubLite, LLC."), 'SubLite Student Account Password Reset', $msg);

        $this->success('A link to reset your password has been sent to your email. If you do not receive it in the next hour, check your spam folder or whitelist info@sublite.net. <a href="mailto: info@sublite.net">Contact us</a> if you have any further questions.');
        $this->render('forgotpass');
        return;
      }

      $this->error($err);
      $this->render('forgotpass', $data);
    }

    function whereto() {
      $this->requireLogin();

      $this->render('student/whereto');
    }

    function view() {
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and
        ($entry = StudentModel::getByID($id = new MongoId($_GET['id']))) != NULL,
        $err, 'unknown student');

      // Code
      if ($this->isValid()) {
        $data = $this->data($entry);
        if ($data['photo'] == 'assets/gfx/defaultpic.png')
            $data['photo'] = $GLOBALS['dirpreFromRoute'] . $data['photo'];
        $data['studentid'] = new MongoId($_GET['id']);
        self::render('student/profile', $data);
      }

      $this->error($err);
      self::render('notice');
    }

    function loggedIn() {
      return isset($_SESSION['loggedinstudent']);
    }
    function requireLogin() {
      if (self::loggedIn()) {
        global $MStudent;
        // Params to vars
        $email = $_SESSION['email'];
        $pass = $_SESSION['pass'];
        $skippass = isset($_SESSION['skippass']);

        // Validations
        self::startValidations();
        self::validate(($entry = $MStudent->get($email)) != NULL,
          $err, 'unknown email');
        if (!$skippass) {
          self::validate(password_verify($pass, $entry['pass']) or $entry['pass'] == md5($pass),
            $err, 'invalid password');
        }

        if (!self::isValid()) {
          self::logout();
        }
      } else {
        self::logout();
      }
    }
    function logout() {
      session_unset();
      self::redirect('index');
    }
  }

  GLOBALvarSet('CStudent', new StudentController());
?>
