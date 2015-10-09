<?php
  require_once($GLOBALS['dirpre'].'tests/TestFixture.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Application.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationStudent.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationJob.php');

  class ApplicationTest extends Test implements TestInterface {
    public static function run() {
      $class = get_called_class();

      TEST($class, "$class.student.saveEditSubmitAndDelete", function($class) {
        // Create job
        global $MJob;
        $jobId = new MongoId($MJob->save([], false));

        $studentId = new MongoId();

        // make sure saving an application for a job with no application
        // fails
        EQ(null, ApplicationStudent::save($jobId, $studentId, []));

        // create application for job
        $questionId = new MongoId();
        $questions = [$questionId];
        ApplicationJob::createOrUpdate($jobId, $questions);
        $answers = [['_id' => $questionId, 'ans' => 'swag']];
        $firstApplication =
          ApplicationStudent::save($jobId, $studentId, $answers);
        NEQ(null, $firstApplication);
        $applicationId = $firstApplication->getId();
        NEQ(null, $firstApplication->getQuestions());
        EQ('swag', $firstApplication->getQuestions()[0]['ans']);

        // edit application
        ApplicationStudent::edit(
          $applicationId, [['_id' => $questionId, 'ans' => 'qswag']]);
        $editedApplication = ApplicationModel::getSavedForJob($jobId)[0];
        EQ('qswag', $editedApplication['questions'][0]['ans']);

        // submit application
        TRUE(ApplicationStudent::submitSaved($applicationId));
        TRUE(ApplicationModel::checkApplicationSubmitted($applicationId));

        // submit new application
        $secondStudent = new MongoId();
        $secondApplication =
          ApplicationStudent::submitNew($jobId, $secondStudent, $answers);
        TRUE(ApplicationModel::checkApplicationSubmitted(
          $secondApplication->getId()));
        //var_dump($secondApplication);

        // create, save, and delete application
        $thirdStudent = new MongoId();
        $applicationToDelete =
          ApplicationStudent::save($jobId, $thirdStudent, $answers);
        TRUE(ApplicationModel::applicationExists($jobId, $thirdStudent));
        //TRUE(false, 'TODO: Finish this test');
      });

      TEST($class, "$class.student.getUnclaimedAndClaimed", function($class) {
        TRUE(false, 'TODO: Write test');
      });

      TEST($class, "$class.job.createOrUpdate", function ($class) {
        // Try to create/update application for nonexistent job
        FALSE(ApplicationJob::createOrUpdate(new MongoId(), []));

        // Create job
        global $MJob;
        $jobId = new MongoId($MJob->save([], false));

        // Try to create application for job
        $oldQuestionId = new MongoId();
        $oldQuestions = [$oldQuestionId];
        TRUE(ApplicationJob::createOrUpdate($jobId, $oldQuestions));
        EQ($MJob::getApplicationQuestionIds($jobId), $oldQuestions);

        // Try to change one question in application for job
        $newQuestionId = new MongoId();
        $newQuestions = [$newQuestionId];
        TRUE(ApplicationJob::createOrUpdate($jobId, $newQuestions));
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