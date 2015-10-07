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
        global $MJob;
        $jobId = new MongoId($MJob->save(array(), false));

        // Try to create application for job
        $oldQuestionId = new MongoId();
        $oldQuestions = array($oldQuestionId);
        TRUE(
          ApplicationJob::createOrUpdate($jobId, $oldQuestions));
        EQ($MJob::getApplicationQuestionIds($jobId), $oldQuestions);

        // Try to change one question in application for job
        $newQuestionId = new MongoId();
        $newQuestions = array($newQuestionId);
        TRUE(
          ApplicationJob::createOrUpdate($jobId, $newQuestions));
        EQ($MJob::getApplicationQuestionIds($jobId), $newQuestions);

        TRUE(false,
          'TODO: Check to make sure student applications are updated right.');
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