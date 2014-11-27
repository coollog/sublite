<?php
  require_once('controllers/Controller.php');

  class RecruiterController extends Controller {
    function home() {

    }
    
    function register() {
      global $params;
      // Params to vars
      $email = clean($params['email']);
      $pass = crypt($params['pass']);
      $firstname = clean($params['firstname']);
      $lastname = clean($params['lastname']);
      $company = clean($params['company']);
      $title = clean($params['title']);
      $phone = clean($params['phone']); // VALIDATE THIS
      
      // Validations
      startValidations();
      validate(filter_var($email, FILTER_VALIDATE_EMAIL), 
        $err, 'invalid email');

      // Code
      if ($this->isValid()) {
        $this->render('profile');
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('register');
    }

    function login() {
      // USE SAME TEMPLATE
    }

    function edit() {
      // USE SAME TEMPLATE
    }

    function view() {
      // USE SAME TEMPLATE
    }
  }

  $CRecruiter = new RecruiterController();

?>