<?php
  require_once($dirpre.'includes/globals.php');
  require_once($dirpre.'includes/functions/utilities.php');

  // Configurations and setup
  require_once($dirpre.'config.php');
  date_default_timezone_set('America/New_York');

  // Error reporting
  require_once($dirpre.'includes/error.php');
  require_once($dirpre.'tests/invariant.php');

  header('Content-Type: text/html; charset=utf-8');

  header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  require_once($dirpre.'includes/functions/hash_equals.php');
  require_once($dirpre.'includes/functions/geocode.php');
  require_once($dirpre.'includes/functions/sendgmail.php');
  require_once($dirpre.'includes/functions/lib_autolink.php');
  require_once($dirpre.'includes/schools.php');

  // Require other header files
  require_once($dirpre.'controllers/AppController.php');
  require_once($dirpre.'controllers/MigrationsController.php');
  require_once($dirpre.'controllers/CompanyController.php');
  require_once($dirpre.'controllers/JobController.php');
  require_once($dirpre.'controllers/RecruiterController.php');
  require_once($dirpre.'controllers/S3/S3Controller.php');
  require_once($dirpre.'controllers/StudentController.php');
  require_once($dirpre.'controllers/MessageController.php');
  require_once($dirpre.'controllers/StatsController.php');
  require_once($dirpre.'controllers/SubletController.php');
  require_once($dirpre.'controllers/SocialController.php');
  require_once($dirpre.'controllers/AdminController.php');
  require_once($dirpre.'controllers/ApplicationController.php');
  require_once($dirpre.'controllers/ApplicationControllerAJAX.php');

  require_once($dirpre.'models/AppModel.php');
  require_once($dirpre.'models/CompanyModel.php');
  require_once($dirpre.'models/JobModel.php');
  require_once($dirpre.'models/RecruiterModel.php');
  require_once($dirpre.'models/StudentModel.php');
  require_once($dirpre.'models/MessageModel.php');
  require_once($dirpre.'models/StatsModel.php');
  require_once($dirpre.'models/SubletModel.php');
  require_once($dirpre.'models/SocialModel.php');
  require_once($dirpre."models/QuestionModel.php");
  require_once($dirpre.'models/ApplicationModel.php');

  // Other necessary modules.
  require_once($dirpre.'router.php');
?>
