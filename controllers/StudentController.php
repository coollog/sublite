<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class StudentController extends Controller {

    function data($data) {
      $lastname = clean($data['lastname']);
      return array(
        'email' => $email
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
        require_once('../schools.php');
        $me['school'] = $S->nameOf($me['email']);
      }

      $this->render('home', $me);
    }

    function index() {
      global $MApp;
      $stats = $MApp->getStats();
      $users = $stats['recruiters'] + $stats['students'];
      $listings = $stats['jobs'] + $stats['sublets'];

      $this->render('studentindex', array(
        'users' => $users,
        'listings' => $listings,
        'universities' => $stats['universities'],
        'cities' => $stats['cities'],
        'companies' => $stats['companies']
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
      $this->validate(
        ($entry = $MStudent->get($email)) != NULL and 
        $MStudent->login($email, $pass), 
        $err, 'invalid credentials');

      if ($this->isValid()) {
        $_SESSION['loggedinstudent'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        $_SESSION['_id'] = $entry['_id'];
        
        // $this->redirect('home');
        $this->redirect('search');

        return;
      }
      
      $this->error($err);
      $this->render('studentlogin', $data);
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