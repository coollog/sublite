<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  header('Content-Type: text/html; charset=utf-8');

  // Configurations and setup
  require_once('config.php');
  date_default_timezone_set('America/New_York');

  // Require other header files
  require_once('models/AppModel.php');
  require_once('models/CompanyModel.php');
  require_once('models/JobModel.php');
  require_once('models/RecruiterModel.php');
  require_once('controllers/MigrationsController.php');
  require_once('controllers/CompanyController.php');
  require_once('controllers/JobController.php');
  require_once('controllers/RecruiterController.php');
  require_once('controllers/S3/S3Controller.php');

  header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  // Utility functions
  function clean($s) {
    // TODO Replace with preg_replace
    $s = str_replace ('“', '"', $s);
    $s = str_replace ('”', '"', $s);
    $s = str_replace ('‘', '\'', $s);
    $s = str_replace ('’', '\'', $s);
    $s = str_replace('–', '', $s);
    $s = str_replace('—', '', $s); //by the way these are 2 different dashes
    $s = trim(htmlentities(utf8_encode($s)));
    return $s;
  }
  function idcmp($id1, $id2) {
    return strval($id1) == strval($id2);
  }
  function str2int($str) { 
    return filter_var($str, FILTER_SANITIZE_NUMBER_INT);
  }
  function str2float($str) {
    return filter_var($str, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  }

  require_once('includes/functions/hash_equals.php');
  require_once('includes/functions/geocode.php');
  require_once('includes/functions/sendgmail.php');
?>