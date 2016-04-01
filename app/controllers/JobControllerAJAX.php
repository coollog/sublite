<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface StudentControllerAJAXInterface {
    public static function dashboardSublets();
    public static function dashboardApplications();
  }

  class StudentControllerAJAX extends StudentController
                              implements StudentControllerAJAXInterface {
    public static function dashboardSublets() {


      echo toJSON([ 'sublets' => $data ]);
    }
  }
?>