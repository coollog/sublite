<?php
  require_once($GLOBALS['dirpre'].'tests/TestFixture.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Application.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationStudent.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationJob.php');

  class ApplicationTest extends Test implements TestInterface {
    public static function run() {
      $class = get_called_class();

      TEST($class, "$class.job.createOrUpdate", function ($class) {
        // Try to create/update application for nonexistent job
        FALSE(ApplicationJob::createOrUpdate(new MongoId(), array()));

        // Create job

        //
      });
    }

    public static function start() {
      self::$MApplicationTest = new ApplicationModel();
    }

    public static function end() {
      self::$MApplicationTest = null;
    }

    private static $MApplicationTest;
  }
?>