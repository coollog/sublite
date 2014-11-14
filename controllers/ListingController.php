<?php
  require_once('controllers/Controller.php');

  class ListingController extends Controller {
    function home() {

    }

    function create() {
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

    function edit() {
      
    }
    
    function view() {
      
    }
  }

  $CListing = new ListingController();

?>