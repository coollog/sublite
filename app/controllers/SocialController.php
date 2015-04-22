<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class SocialController extends Controller {
    function index() {
      global $MApp;

      $this->render('socialindex');
    }
  }

  $CSocial = new SocialController();
?>