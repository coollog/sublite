<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class JobPortalController extends Controller {
    public static function viewPortal() {
      self::render('jobs/portal/portal', []);
    }
  }
?>
