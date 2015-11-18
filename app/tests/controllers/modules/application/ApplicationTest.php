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
        $jobId = new MongoId(JobModel::save([], false));

        $studentId = new MongoId();

        // make sure saving an application for a job with no application
        // fails
        EQ(null, ApplicationStudent::save($jobId, $studentId, []));

        // create application for job
        $questionId = new MongoId();
        $questions = [$questionId];
        ApplicationJob::createOrUpdate($jobId, $questions);
        $answers = [['_id' => $questionId, 'answer' => 'swag']];
        $firstApplication =
          ApplicationStudent::save($jobId, $studentId, $answers);
        NEQ(null, $firstApplication);
        $applicationId = $firstApplication->getId();
        NEQ(null, $firstApplication->getQuestions());
        EQ('swag', $firstApplication->getQuestions()[0]['answer']);

        // edit application
        ApplicationStudent::edit(
          $applicationId, [['_id' => $questionId, 'answer' => 'qswag']]);
        $editedApplication = ApplicationModel::getSavedForJob($jobId)[0];
        EQ('qswag', $editedApplication['questions'][0]['answer']);

        // submit application
        TRUE(ApplicationStudent::submitSaved($applicationId));
        TRUE(ApplicationModel::checkSubmitted($applicationId));

        // submit new application
        $secondStudent = new MongoId();
        $secondApplication =
          ApplicationStudent::submitNew($jobId, $secondStudent, $answers);
        TRUE(ApplicationModel::checkSubmitted(
          $secondApplication->getId()));

        // create, save, and delete application
        $thirdStudent = new MongoId();
        $applicationToDelete =
          ApplicationStudent::save($jobId, $thirdStudent, $answers)->getId();
        TRUE(ApplicationModel::applicationExists($jobId, $thirdStudent));
        TRUE(ApplicationStudent::deleteSaved($applicationToDelete));
      });

      TEST($class, "$class.student.getUnclaimedAndClaimed", function($class) {
        TRUE(false, 'TODO: Write test');
      });

      TEST($class, "$class.job.createOrUpdate", function ($class) {
        // Try to create/update application for nonexistent job
        FALSE(ApplicationJob::createOrUpdate(new MongoId(), []));

        // Create job
        $jobId = new MongoId(JobModel::save([], false));

        // Try to create application for job
        $oldQuestionId = new MongoId();
        $oldQuestions = [$oldQuestionId];
        TRUE(ApplicationJob::createOrUpdate($jobId, $oldQuestions));
        EQ(JobModel::getApplicationQuestionIds($jobId), $oldQuestions);

        // Try to change one question in application for job
        $newQuestionId = new MongoId();
        $newQuestions = [$newQuestionId];
        TRUE(ApplicationJob::createOrUpdate($jobId, $newQuestions));
        EQ(JobModel::getApplicationQuestionIds($jobId), $newQuestions);

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