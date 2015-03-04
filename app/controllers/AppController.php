<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class AppController extends Controller {
    function faq() {
      $this->render('faq');
    }

    function privacy() {
      $this->render('privacy');
    }

    function terms() {
      $this->render('terms');
    }

    function team() {
      $this->render('team');
    }
  }

  $CApp = new AppController();

?>