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
        TRUE(ApplicationJob::createOrUpdate($jobId, $oldQuestions));
        EQ($MJob::getApplicationQuestionIds($jobId), $oldQuestions);

        // Try to change one question in application for job
        $newQuestionId = new MongoId();
        $newQuestions = array($newQuestionId);
        TRUE(ApplicationJob::createOrUpdate($jobId, $newQuestions));
        EQ($MJob::getApplicationQuestionIds($jobId), $newQuestions);

        TRUE(false,
          'TODO: Check to make sure student applications are updated right.');
      });

      TEST($class, "$class.student.saveEditSubmitAndDelete", function($class) {
        // Create job
        global $MJob;
        $jobId = new MongoId($MJob->save(array(), false));

        $studentId = new MongoId();

        // make sure saving an application for a job with no application
        // fails
        EQ(ApplicationStudent::save($jobId, $studentId, array()), null);

        // create application for job
        $questionId = new MongoId();
        $questions = array($questionId);
        ApplicationJob::createOrUpdate($jobId, $questions);
        $studentAnswers =
          array(array('_id' => $questionId, 'answer' => 'swag'));
        NEQ(
          ApplicationStudent::save($jobId, $studentId, $studentAnswers), null);

        TRUE(false, 'TODO: Finish this test');
      });

      TEST($class, "$class.student.getUnclaimedAndClaimed", function($class) {
        TRUE(false, 'TODO: Write test');
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