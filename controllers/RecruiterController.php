<?php
  require_once('controllers/Controller.php');

  class RecruiterController extends Controller {
    function home() {
      $this->requireLogin();
      $id = $_SESSION['_id'];
      echo "<a href='recruiter.php?id=$id'>View Profile</a>";
    }
    
    function register() {
      if (!isset($_POST['register'])) { $this->render('register'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      $email = clean($params['email']);
      $pass = crypt($params['pass']);
      $firstname = clean($params['firstname']);
      $lastname = clean($params['lastname']);
      $company = clean($params['company']);
      $title = clean($params['title']);
      $phone = clean($params['phone']); // VALIDATE THIS
      $data = array(
        'email' => $email, 'pass' => $pass, 'firstname' => $firstname, 
        'lastname' => $lastname, 'company' => $company, 'title' => $title,
        'phone' => $phone
      );
      
      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      $this->validate(!$MRecruiter->exists($email),
        $err, 'email taken');

      // Code
      if ($this->isValid()) {
        $MRecruiter->save($data);
        $_POST['login'] = true; $this->login();
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('register', $data);
    }

    function login() {
      if (!isset($_POST['login'])) { $this->render('login'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      global $email;
      $email = clean($params['email']);
      $pass = $params['pass'];
      $data = array(
        'email' => $email
      );
      
      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      $this->validate(
        ($entry = $MRecruiter->get($email)) != NULL and 
        $MRecruiter->login($email, $pass), 
        $err, 'invalid credentials');

      if ($this->isValid()) {
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        $_SESSION['_id'] = $entry['_id'];
        
        $this->redirect('home');
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION

      $this->render('login', $data);
    }

    function edit() {
      $this->requireLogin();
      
      global $params, $MRecruiter;
      // Params to vars
      $email = $_SESSION['email'];
      $pass = $_SESSION['pass'];
      
      // Validations
      $this->startValidations();

      // Code
      if ($this->isValid()) {
        if (!isset($_POST['edit'])) { 
          $this->render('editprofile', 
            $MRecruiter->data($MRecruiter->get($email))); return;
        }

        $firstname = clean($params['firstname']);
        $lastname = clean($params['lastname']);
        $company = clean($params['company']);
        $title = clean($params['title']);
        $phone = clean($params['phone']); // VALIDATE THIS
        $data = array(
          'email' => $email, 'pass' => $pass, 'firstname' => $firstname, 
          'lastname' => $lastname, 'company' => $company, 'title' => $title,
          'phone' => $phone
        );
        // Validations


        if ($this->isValid()) {
          $id = $MRecruiter->save($data);
          echo 'profile saved'; // REFACTOR TO A SUCCESS DISPLAY FUNCTION
          $this->render('editprofile', $data);
          return;
        }
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('editprofile', $data);
    }

    function view() {
      $this->requireLogin();
      
      global $params, $MRecruiter;
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MRecruiter->getByID($_GET['id'])) != NULL, 
        $err, 'unknown recruiter');

      // Code
      if ($this->isValid()) {
        $this->render('recruiter', $MRecruiter->data($entry));
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
    }

    function requireLogin() {
      if (isset($_SESSION['email'])) {
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