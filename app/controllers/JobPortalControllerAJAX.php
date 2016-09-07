<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface JobPortalControllerAJAXInterface {
    public static function mostPopular();
    public static function recent();
    public static function byIndustry();
  }

  class JobPortalControllerAJAX extends JobController
                                implements JobPortalControllerAJAXInterface {
    public static function mostPopular() {
      global $params;
      $skip = $params['skip'];
      $count = $params['count'];

      // Build the query.

      // Issue the query.

      // Massage the results.

      // Send back JSON.
    }

    public static function recent() {

    }

    public static function byIndustry() {

    }


    // Takes a job document and extracts just the information to send back.
    private static function normalizeJob() {

    }
  }
?>