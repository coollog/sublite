<?php
  require_once('controllers/Controller.php');

  class RecruiterController extends Controller {
    function home() {
      $this->requireLogin();
      echo 'yay!';
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
      
      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      $this->validate(($entry = $MRecruiter->get($email)) == NULL, 
        $err, 'email taken');

      // Code
      if ($this->isValid()) {
        $id = $MRecruiter->save($email, $pass, $firstname, $lastname, 
                                $company, $title, $phone);
        $this->login();
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('register', array(
        'email' => $email, 'firstname' => $firstname, 'lastname' => $lastname,
        'company' => $company, 'title' => $title, 'phone' => $phone
      ));
    }

    function login() {
      if (!isset($_POST['login'])) { $this->render('login'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      global $email;
      $email = clean($params['email']);
      $pass = $params['pass'];
      
      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');
      $this->validate(
        ($entry = $MRecruiter->get($email)) != NULL and 
        hash_equals($entry['pass'], crypt($pass, $entry['pass'])), 
        $err, 'invalid credentials');

      if ($this->isValid()) {
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        
        $this->redirect('home');
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION

      $this->render('login', array(
        'email' => $email
      ));
    }

    function edit() {
      $this->requireLogin();
      if (!isset($_POST['edit'])) { $this->render('editprofile'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      $email = $_SESSION['email'];
      $pass = $_SESSION['pass'];
      $firstname = clean($params['firstname']);
      $lastname = clean($params['lastname']);
      $company = clean($params['company']);
      $title = clean($params['title']);
      $phone = clean($params['phone']); // VALIDATE THIS
      
      // Validations
      $this->startValidations();
      $this->validate(($entry = $MRecruiter->get($email)) == NULL, 
        $err, 'email not found');

      // Code
      if ($this->isValid()) {
        $id = $MRecruiter->save($email, $pass, $firstname, $lastname, 
                                $company, $title, $phone);
        echo 'profile saved'; // REFACTOR TO A SUCCESS DISPLAY FUNCTION
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $vEmail = $email;
      $this->render('editprofile', array(
        'firstname' => $firstname, 'lastname' => $lastname,
        'company' => $company, 'title' => $title, 'phone' => $phone
      ));
    }

    function view() {
      // USE SAME TEMPLATE
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