<?php
  require_once(APPDIR.'includes/globals.php');
  require_once(APPDIR.'includes/functions/utilities.php');

  // Configurations and setup
  require_once(APPDIR.'config.php');
  date_default_timezone_set('America/New_York');

  // Error reporting
  require_once(APPDIR.'includes/error.php');
  require_once(APPDIR.'tests/invariant.php');

  header('Content-Type: text/html; charset=utf-8');

  header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  if (!function_exists('hash_equals')) {
    require_once(APPDIR.'includes/functions/hash_equals.php');
  }
  require_once(APPDIR.'includes/functions/geocode.php');
  require_once(APPDIR.'includes/functions/sendgmail.php');
  require_once(APPDIR.'includes/functions/lib_autolink.php');
  require_once(APPDIR.'includes/schools.php');
  require_once(APPDIR.'includes/classes/GeoJSON.php');

  // Global modules
  require_once(APPDIR.'controllers/modules/Geocode.php');

  // Require other header files
  require_once(APPDIR.'controllers/AppController.php');
  require_once(APPDIR.'controllers/MigrationsController.php');
  require_once(APPDIR.'controllers/CompanyController.php');
  require_once(APPDIR.'controllers/CompanyControllerAJAX.php');
  require_once(APPDIR.'controllers/JobController.php');
  require_once(APPDIR.'controllers/JobControllerAJAX.php');
  require_once(APPDIR.'controllers/RecruiterController.php');
  require_once(APPDIR.'controllers/S3/S3ControllerAJAX.php');
  require_once(APPDIR.'controllers/StudentController.php');
  require_once(APPDIR.'controllers/StudentControllerAJAX.php');
  require_once(APPDIR.'controllers/MessageController.php');
  require_once(APPDIR.'controllers/StatsController.php');
  require_once(APPDIR.'controllers/SubletController.php');
  require_once(APPDIR.'controllers/SocialController.php');
  require_once(APPDIR.'controllers/AdminController.php');
  require_once(APPDIR.'controllers/ApplicationController.php');
  require_once(APPDIR.'controllers/ApplicationControllerAJAX.php');
  require_once(APPDIR.'controllers/PaymentControllerAJAX.php');
  require_once(APPDIR.'controllers/CompanyControllerAJAX.php');
  require_once(APPDIR.'controllers/StatsControllerAJAX.php');
  require_once(APPDIR.'controllers/JobPortalController.php');
  require_once(APPDIR.'controllers/JobPortalControllerAJAX.php');

  require_once(APPDIR.'models/AppModel.php');
  require_once(APPDIR.'models/CompanyModel.php');
  require_once(APPDIR.'models/JobModel.php');
  require_once(APPDIR.'models/RecruiterModel.php');
  require_once(APPDIR.'models/StudentModel.php');
  require_once(APPDIR.'models/MessageModel.php');
  require_once(APPDIR.'models/StatsModel.php');
  require_once(APPDIR.'models/SubletModel.php');
  require_once(APPDIR.'models/SocialModel.php');
  require_once(APPDIR."models/QuestionModel.php");
  require_once(APPDIR.'models/ApplicationModel.php');
  require_once(APPDIR.'models/GeocodeModel.php');

  // Other necessary modules.
  require_once(APPDIR.'Router.php');
?>
