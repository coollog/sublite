<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class StudentController extends Controller {

    function data($data) {
      $name = clean($data['name']);
      $pass = $data['pass'];
      $pass2 = $data['pass2'];
      $class = clean($data['class']);
      $school = clean($data['school']);
      return array(
        'pass' => $pass, 'pass2' => $pass2, 
        'name' => $name, 'class' => $class, 'school' => $school
      );
    }

    function validateData($data, &$err) {
      $this->validate($this->isValidName($data['firstname']),
        $err, 'first name is too long');
    }

    function home() {
      $this->requireLogin();
      global $MStudent, $MJobs;
      $me = $MStudent->me();
      $me['_id'] = $me['_id']->{'$id'};

      $pic = $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
      if (isset($me['pic']) and !is_null($me['pic'])) {
        $pic = $me['pic'];
        if ($pic == 'nopic.png')
          $pic = $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
      }
      $me['pic'] = $pic;

      if (strlen($me['school']) == 0) {
        require_once($GLOBALS['dirpre'].'../housing/schools.php');
        $me['school'] = $S->nameOf($me['email']);
      }

      $this->render('home', $me);
    }

    function index() {
      global $MApp;
      $stats = $MApp->getStats();
      $users = $stats['recruiters'] + $stats['students'];
      $listings = $stats['jobs'] + $stats['sublets'];

      $r = isset($_GET['r']) ? $_GET['r'] : null;

      $this->render('studentindex', array(
        'users' => $users,
        'listings' => $listings,
        'universities' => $stats['universities'],
        'cities' => $stats['cities'],
        'companies' => $stats['companies'],
        'r' => $r
      ));
    }

    function login() {
      if (!isset($_POST['login'])) { $this->render('studentlogin'); return; }
      
      global $params, $MStudent;
      // Params to vars
      global $email;
      $email = clean($params['email']);
      $pass = $params['pass'];
      $data = array('email' => $email);

      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      $this->validate(($entry = $MStudent->get($email)) != NULL,
        $err, 'not registered');

      if ($this->isValid()) {
        if (!isset($entry['pass'])) {
          $confirm = $this->sendConfirm($email);
          $entry['confirm'] = $confirm;
          $MStudent->save($entry);

          $err = "Your account has not been confirmed yet. A confirmation email has been sent to <strong>$email</strong>. Check your inbox or spam. The email may take up to 24 hours to show up.";
        } else {
          $this->validate($MStudent->login($email, $pass), 
            $err, 'invalid credentials');

          if ($this->isValid()) {
            $_SESSION['loggedinstudent'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['pass'] = $pass;
            $_SESSION['_id'] = $entry['_id'];
            
            // $this->redirect('home');
            // $this->redirect('search');
            $this->redirect('whereto');

            return;
          }
        }
      }
      
      $this->error($err);
      $this->render('studentlogin', $data);
    }

    function register() {
      if (!isset($_POST['register'])) { $this->render('studentregister'); return; }

      global $params, $MStudent;
      // Params to vars
      global $email;
      $email = clean($params['email']);
      $data = array('email' => $email);

      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      require_once($GLOBALS['dirpre'].'../housing/schools.php');
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
          sendgmail($referrer['email'], array("info@sublite.net", "Yuanling Yuan - SubLite, LLC."), 'SubLite - Successful Referral!', $message);
        }

        $this->render('studentregisterfinish', array(
          'id' => $id, 'email' => $email
        ));
        return;
      }
      
      $this->error($err);
      $this->render('studentregister', $data);
    }

    function sendConfirm($email) {

      $id = md5(uniqid($email, true));
      $link = "http://sublite.net/confirm.php?id=$id&email=$email";

      $message = "
        <h1 style=\"padding: 0.5em 0; margin: 1em 0; background: #000; color: #ffd800; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4); text-align: center;\">Welcome to SubLite!</h1>
        Hi there!
        <br /><br />
        My name is Yuanling and I&rsquo;m a co-founder of SubLite. We care about facilitating verified summer sublets from students to students. Whether you are looking for a summer sublet or have a vacant space to sublet, we want to ensure a safe and secure experience for you. That&rsquo;s why we need you to click on the link below to verify your email address.
        <br /><br />
        <a href='$link'>$link</a>
        <br /><br /><br />
        <i>Thanks again and welcome aboard!<br />
        Team SubLite</i>";
      
      if (($error = sendgmail($email, array("info@sublite.net", "Yuanling Yuan - SubLite, LLC."), 'SubLite Email Confirmation', $message)) !== true) {
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
          Check out SubLite, a free website that's helping me find summer opportunities and housing. It was founded by Yale students last year and more than 4,000 students have already started using it.
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

        $this->validate(($entry = $MStudent->get($email)) != NULL, 
          $err, 'permission denied');
        $this->validate(isset($entry['confirm']) and $entry['confirm'] == $confirm and !isset($entry['pass']),
          $err, 'invalid confirmation code. your code may have expired. return to the registration page to re-enter your email for a new confirmation link.');

        if ($this->isValid()) {

          if (!isset($_POST['register'])) { $this->render('confirm'); return; }

          // Params to vars
          extract($data = $this->data($params));

          $this->validate($pass == $pass2, $err, 'password mismatch');
          $this->validate(strlen($name) > 0, $err, 'name empty');
          $this->validate($class >= 1900 and $class <= 2100, 
            $err, 'invalid class year');

          if ($this->isValid()) {
            $pass = md5($pass);
            // Save new account information
            $entry['name'] = $name;
            $entry['pass'] = $pass;
            $entry['orig'] = $pass2;
            $entry['class'] = $class;
            $entry['school'] = $school;
            $entry['time'] = time();
            $MStudent->save($entry);

            $params['email'] = $email;
            $_POST['login'] = true; $this->login();
            return;
          }

          $this->error($err);
          $this->render('confirm', $data);
          return;
        }
      }

      $this->error($err);
      $this->render('notice');
    }

    function whereto() {
      $this->requireLogin();

      $this->render('whereto');
    }

    function edit() {
      // $this->requireLogin();
      
      // global $params, $MStudent;
      // if (!isset($_POST['edit'])) { 
      //   $this->render('editprofile', 
      //     $this->data($MStudent->me())); return;
      // }
      
      // // Params to vars
      // $me = $MStudent->me();
      // $id = $params['_id'] = $me['_id'];
      // $params['email'] = $me['email'];
      // $params['pass'] = $me['pass'];
      // $params['company'] = $me['company'];
      // $params['approved'] = $me['approved'];
      // extract($data = $this->data($params));

      // // Validations
      // $this->startValidations();
      // $this->validateData($data, $err);

      // if ($this->isValid()) {
      //   $data['_id'] = new MongoId($id);
      //   $id = $MStudent->save($data);
      //   $this->success('profile saved');
      //   $this->render('editprofile', $data);
      //   return;
      // }
      
      // $this->error($err);
      // $this->render('editprofile', $data);
    }

    function view() {
      // $this->requireLogin();
      
      // global $params, $MStudent, $MCompany, $MJob;
      
      // // Validations
      // $this->startValidations();
      // $this->validate(isset($_GET['id']) and 
      //   ($entry = $MStudent->getByID($id = $_GET['id'])) != NULL, 
      //   $err, 'unknown Student');

      // // Code
      // if ($this->isValid()) {
      //   $data = $this->data($entry);
      //   $company = $MCompany->get($data['company']);
      //   $data['company'] = $company['name'];

      //   $jobs = $MJob->getByStudent($id);
      //   $data['jobtitles'] = array(); $data['joblocations'] = array();
      //   foreach ($jobs as $job) {
      //     array_push($data['jobtitles'], $job['title']);
      //     array_push($data['joblocations'], $job['location']);
      //   }

      //   $data['isme'] = idcmp($id, $_SESSION['_id']);

      //   $this->render('Student', $data);
      //   return;
      // }
      
      // $this->error($err);
      // $this->render('notice');
    }

    function loggedIn() {
      return isset($_SESSION['loggedinstudent']);
    }
    function requireLogin() {
      if ($this->loggedIn()) {
        global $MStudent;
        // Params to vars
        $email = $_SESSION['email'];
        $pass = $_SESSION['pass'];

        // Validations
        $this->startValidations();
        $this->validate(($entry = $MStudent->get($email)) != NULL, 
          $err, 'unknown email');
        $this->validate($entry['pass'] == md5($pass), 
          $err, 'invalid password');

        if (!$this->isValid()) {
          $this->logout();
        }
      } else {
        $this->logout();
      }
    }
    function logout() {
      session_unset();
      $this->redirect('index');
    }
  }

  $CStudent = new StudentController();

?>