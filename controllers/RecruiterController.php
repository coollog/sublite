<?php
  require_once('includes/Controller.php');

  class RecruiterController extends Controller {
    function home() {

    }
    function register() {
      global $params;
      // Params to vars
      
      // Validations
      startValidations();

      // Code
      if ($this->valid()) {
        $this->render(/*SOMETHING*/);
        return;
      }
      
      $this->render(/*SOMETHING*/);
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