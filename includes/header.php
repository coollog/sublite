<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  // Configurations and setup
  require_once('config.php');
  date_default_timezone_set('America/New_York');

  // Require other header files
  require_once('models/JobModel.php');
  require_once('models/RecruiterModel.php');
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
    return trim(htmlentities($s));
  }
  require_once('includes/hash_equals.php');
?>