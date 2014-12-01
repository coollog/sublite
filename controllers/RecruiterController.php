<?php
  require_once('controllers/Controller.php');

  class RecruiterController extends Controller {
    function data($data) {
      $email = $data['email'];
      $pass = $data['pass'];
      $firstname = clean($data['firstname']);
      $lastname = clean($data['lastname']);
      $company = clean($data['company']);
      $title = clean($data['title']);
      $phone = clean($data['phone']); // VALIDATE THIS
      return array(
        'email' => $email, 'pass' => $pass, 'firstname' => $firstname, 
        'lastname' => $lastname, 'company' => $company, 'title' => $title,
        'phone' => $phone
      );
    }

    function home() {
      $this->requireLogin();
      $id = $_SESSION['_id'];
      echo "<a href='recruiter.php?id=$id'>View Profile</a>";
    }
    
    function register() {
      if (!isset($_POST['register'])) { $this->render('register'); return; }
      
      global $params, $MRecruiter;
      // Params to vars
      $data = $params;
      $data['email'] = clean($params['email']);
      $data['pass'] = crypt($params['pass']);
      extract($data = $this->data($data));
      
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
      if (!isset($_POST['edit'])) { 
        $this->render('editprofile', 
          $this->data($MRecruiter->get($_SESSION['email']))); return;
      }
      
      global $params, $MRecruiter;
      // Params to vars
      $params['_id'] = $_SESSION['_id'];
      $params['email'] = $_SESSION['email'];
      $params['pass'] = $_SESSION['pass'];
      extract($data = $this->data($params));

      // Validations
      $this->startValidations();

      if ($this->isValid()) {
        $id = $MRecruiter->save($data);
        echo 'profile saved'; // REFACTOR TO A SUCCESS DISPLAY FUNCTION
        $this->render('editprofile', $data);
        return;
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
        $this->render('recruiter', $this->data($entry));
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