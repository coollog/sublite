<?php
  require_once('controllers/Controller.php');

  class RecruiterController extends Controller {
    // Validation functions
    function isValidName($name) { // Works for first or last name
      if(strlen($name) > 100) return false;
      if(preg_match('`[0-9]`', $name)) return false;
      return true;
    }

    function data($data) {
      $email = $data['email'];
      $pass = $data['pass'];
      $firstname = clean($data['firstname']);
      $lastname = clean($data['lastname']);
      $company = clean($data['company']);
      $title = clean($data['title']);
      $phone = isset($data['phone']) ? clean($data['phone']) : '';
      $photo = isset($data['photo']) ? 
        clean($data['photo']) : 'assets/gfx/defaultpic.png';
      $approved = $data['approved'];
      return array(
        'email' => $email, 'pass' => $pass, 'firstname' => $firstname, 
        'lastname' => $lastname, 'company' => $company, 'title' => $title,
        'phone' => $phone, 'photo' => $photo, 'approved' => $approved
      );
    }

    function home() {
      $this->requireLogin();
      global $MRecruiter, $MJobs;
      $me = $MRecruiter->me();
      $me['_id'] = $me['_id']->{'$id'};
      $this->render('home', $me);
    }

    function index() {
      $this->render('index');
    }

    function faq() {
      $this->render('faq');
    }
    
    function register() {
      if (!isset($_POST['register'])) { $this->render('register'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      $data = $params;
      $data['email'] = clean($params['email']);
      $data['pass'] = crypt($params['pass']);
      $data['approved'] = 'pending';
      extract($data = $this->data($data));
      
      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      $this->validate(!$MRecruiter->exists($email),
        $err, 'email taken');
      $this->validate($params['pass'] == $params['pass2'], 
        $err, 'password mismatch');
      $this->validate($this->isValidName($data['firstname']),
        $err, 'first name is too long');
      $this->validate($this->isValidName($data['lastname']),
        $err, 'last name is too long');


      // Code
      if ($this->isValid()) {
        // Register the user, send a notice to us, and log him in
        $MRecruiter->save($data);
        $approveurl = "http://" . $GLOBALS['domain'] . "/approve.php?p=$pass";
        $msg = "New recruiter registered needs approval of account.
                <br />Registration information:<br />
                Email: $email<br />
                First Name: $firstname<br />
                Last Name: $lastname<br />
                Company: $company<br />
                Title: $title<br /><br />
                To approve: <a href=\"$approveurl\">$approveurl</a>";
        sendgmail(array('qingyang.chen@gmail.com', 'tony.jiang@yale.edu', 'yuanling.yuan@yale.edu', 'shirley.guo@yale.edu', 'alisa.melekhina@gmail.com', 'michelle.chan@yale.edu'), 'info@sublite.net', 'New Recruiter Requires Approval', $msg);
        $_POST['login'] = true; $this->login();
        return;
      }
      
      $this->error($err);
      $this->render('register', $data);
    }

    function approve() {
      if (!isset($_GET['p'])) { $this->redirect('index'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      $p = $_GET['p'];

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MRecruiter->getByPass($p)) != NULL, 
        $err, 'invalid');
      $this->validate($entry['approved'] == 'pending',
        $err, 'already approved');

      if ($this->isValid()) {
        $entry['approved'] = 'approved';
        $MRecruiter->save($entry);
        
        echo 'Approved';
        return;
      }
      
      $this->error($err);
    }


    function login() {
      if (!isset($_POST['login'])) { $this->render('login'); return; }
      
      global $params, $MRecruiter;
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
        ($entry = $MRecruiter->get($email)) != NULL and 
        $MRecruiter->login($email, $pass), 
        $err, 'invalid credentials');
      $this->validate($entry['approved'] == 'approved', 
        $err, 'account approval status: ' . $entry['approved']);

      if ($this->isValid()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        $_SESSION['_id'] = $entry['_id'];
        
        if (MongoId::isValid($entry['company'])) $this->redirect('home');
        else $this->redirect('addcompany');

        return;
      }
      
      $this->error($err);
      $this->render('login', $data);
    }

    function edit() {
      $this->requireLogin();
      
      global $params, $MRecruiter;
      if (!isset($_POST['edit'])) { 
        $this->render('editprofile', 
          $this->data($MRecruiter->me())); return;
      }
      
      // Params to vars
      $me = $MRecruiter->me();
      $id = $params['_id'] = $me['_id'];
      $params['email'] = $me['email'];
      $params['pass'] = $me['pass'];
      $params['company'] = $me['company'];
      $params['approved'] = $me['approved'];
      extract($data = $this->data($params));

      // Validations
      $this->startValidations();

      if ($this->isValid()) {
        $data['_id'] = new MongoId($id);
        $id = $MRecruiter->save($data);
        $this->success('profile saved');
        $this->render('editprofile', $data);
        return;
      }
      
      $this->error($err);
      $this->render('editprofile', $data);
    }

    function view() {
      $this->requireLogin();
      
      global $params, $MRecruiter, $MCompany, $MJob;
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MRecruiter->getByID($id = $_GET['id'])) != NULL, 
        $err, 'unknown recruiter');

      // Code
      if ($this->isValid()) {
        $data = $this->data($entry);
        $company = $MCompany->get($data['company']);
        $data['company'] = $company['name'];

        $jobs = $MJob->getByRecruiter($id);
        $data['jobtitles'] = array(); $data['joblocations'] = array();
        foreach ($jobs as $job) {
          array_push($data['jobtitles'], $job['title']);
          array_push($data['joblocations'], $job['location']);
        }

        $data['isme'] = idcmp($id, $_SESSION['_id']);

        $this->render('recruiter', $data);
        return;
      }
      
      $this->error($err);
      $this->render('notice');
    }

    function requireLogin() {
      if (isset($_SESSION['loggedin'])) {
        global $MRecruiter;
        // Params to vars
        $email = $_SESSION['email'];
        $pass = $_SESSION['pass'];

        // Validations
        $this->startValidations();
        $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
          $err, 'invalid email');
        $this->validate(($entry = $MRecruiter->get($email)) != NULL, 
          $err, 'unknown email');
        $this->validate(hash_equals($entry['pass'], crypt($pass, $entry['pass'])), 
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
      $this->redirect('loginregister');
    }
  }

  $CRecruiter = new RecruiterController();

?>