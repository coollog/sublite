<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  header('Content-Type: text/html; charset=utf-8');

  // Configurations and setup
  require_once($GLOBALS['dirpre'].'config.php');
  date_default_timezone_set('America/New_York');

  // Require other header files
  require_once($GLOBALS['dirpre'].'models/AppModel.php');
  require_once($GLOBALS['dirpre'].'models/CompanyModel.php');
  require_once($GLOBALS['dirpre'].'models/JobModel.php');
  require_once($GLOBALS['dirpre'].'models/RecruiterModel.php');
  require_once($GLOBALS['dirpre'].'models/StudentModel.php');
  require_once($GLOBALS['dirpre'].'models/MessageModel.php');
  require_once($GLOBALS['dirpre'].'controllers/MigrationsController.php');
  require_once($GLOBALS['dirpre'].'controllers/CompanyController.php');
  require_once($GLOBALS['dirpre'].'controllers/JobController.php');
  require_once($GLOBALS['dirpre'].'controllers/RecruiterController.php');
  require_once($GLOBALS['dirpre'].'controllers/S3/S3Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/StudentController.php');
  require_once($GLOBALS['dirpre'].'controllers/MessageController.php');

  header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  require_once($GLOBALS['dirpre'].'includes/functions/utilities.php');
  require_once($GLOBALS['dirpre'].'includes/functions/hash_equals.php');
  require_once($GLOBALS['dirpre'].'includes/functions/geocode.php');
  require_once($GLOBALS['dirpre'].'includes/functions/sendgmail.php');
?>
