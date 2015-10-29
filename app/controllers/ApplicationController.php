<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Question.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationJob.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationStudent.php');

  interface ApplicationControllerInterface {
    /**
     * Recruiter viewing applicants for a job.
     */
    public static function applicants(array $restOfRoute);

    /**
     * Recruiter editing an application for a job.
     */
    public static function edit(array $restOfRoute);

    /**
     * Student applying to a job.
     */
    public static function apply(array $restOfRoute);

    /**
     * Recruiter and student viewing an application.
     * Recruiters have extra functionality to report an application.
     */
    public static function view(array $restOfRoute);

    /**
     * AJAX calls made while editing an application.
     */
    public static function createCustom();
    public static function deleteCustom();
    public static function searchCustom();
  }

  class ApplicationController extends Controller
                              implements ApplicationControllerInterface {
    public static function applicants(array $restOfRoute) {
      RecruiterController::requireLogin();

      $jobId = self::getIdFromRoute($restOfRoute);
      if (is_null($jobId)) return;

      // Make sure job exists.
      // Make sure recruiter owns the job.
      if (!self::checkJobExists($jobId)) return;
      if (!self::ownsJob($jobId)) return;

      $job = JobModel::getByIdMinimal($jobId);

      self::render('jobs/applications/applicants', [
        'jobid' => $jobId,
        'jobtitle' => $job['title'],
        'joblocation' => $job['location']
      ]);
    }

    public static function edit(array $restOfRoute) {
      RecruiterController::requireLogin();

      global $params;

      $jobId = self::getIdFromRoute($restOfRoute);
      if (is_null($jobId)) return;

      // Make sure job exists.
      // Make sure recruiter has permission to edit the job.
      if (!self::checkJobExists($jobId)) return;
      if (!self::ownsJob($jobId)) return;

      // Process saving of questions.
      if (self::save($jobId)) return;

      $vanillaQuestions = Question::getAllVanilla();
      $vanillaQuestionsData = [];
      $chosenData = [];

      // Get existing questions.
      $chosenIds = ApplicationModel::getJobApplication($jobId);

      // Remove any existing from $vanillaQuestions.
      if ($chosenIds !== null) {
        $chosenIdHash = arrayToSet($chosenIds);
      }
      foreach ($vanillaQuestions as $question) {
        $id = (string)$question->getId();
        $data = $question->getData();
        $data['hide'] = $chosenIds !== null && isset($chosenIdHash[$id]);
        $vanillaQuestionsData[] = $data;
      }

      // If no application, we show form to create application.
      // Else, we show form to edit existing application.
      if ($chosenIds === null) {
        $createEdit = 'create';
      } else {
        $createEdit = 'edit';

        // Get the text for each $chosenIds.
        foreach ($chosenIds as $_id) {
          $data = QuestionModel::getById($_id, ['text' => 1, 'vanilla' => 1]);
          if ($data === null) continue;

          $text = $data['text'];
          $vanilla = $data['vanilla'];
          $chosenData[] = [
            '_id' => $_id,
            'text' => $text,
            'vanilla' => $vanilla
          ];
        }
      }

      $job = JobModel::getByIdMinimal($jobId);

      self::render('jobs/applications/edit', [
        'createEdit' => $createEdit,
        'vanillaQuestions' => $vanillaQuestionsData,
        'chosen' => $chosenData,
        'jobId' => $jobId,
        'jobTitle' => $job['title'],
        'jobLocation' => $job['location']
      ]);
    }

    public static function apply(array $restOfRoute) {
      global $params;

      StudentController::requireLogin();

      $jobId = self::getIdFromRoute($restOfRoute);
      if (is_null($jobId)) return;

      $studentId = $_SESSION['_id'];
      $application = ApplicationJob::get($jobId);

      // Make sure job exists.
      if (!self::checkJobExists()) return;

      // Make sure application exists.
      if (is_null($application)) {
        self::error("This job does not have an application.");
        self::render('notice');
        return;
      }

      // Saving of application.
      if (isset($params['questions'])) {
        ApplicationStudent::save($jobId, $studentId, $params['questions']);
        return;
      }

      $submitted = false;

      // Submitting of application.
      if ($params) {
        $answers = array();
        foreach ($params as $_id => $answer) {
          $answers[] = ['_id' => $_id, 'answer' => $answer];
        }
        $application = ApplicationStudent::save($jobId, $studentId, $answers);
        $applicationId = $application->getId();

        $submitted = ApplicationStudent::submit($applicationId);
        if ($submitted) {
          self::redirect("../application/$applicationId");
        }

        self::error(
          "You must attach a resume to your profile in order to submit ".
          "an application."
        );
      }

      $entry = JobModel::getById($jobId);
      $companyId = $entry['company'];
      $company = CompanyModel::getById($companyId);

      $questions = array();

      if (ApplicationModel::applicationExists($jobId, $studentId)) {
        $application = new ApplicationStudent(
          ApplicationModel::getApplication($jobId, $studentId));
        $applicationId = $application->getId();
        $submitted = ApplicationModel::checkApplicationSubmitted($application->getId());

        if ($submitted) {
          self::redirect("../application/$applicationId");
        }

        foreach ($application->getQuestions() as $question) {
          $_id = $question['_id'];
          $questions[] = [
            '_id' => $_id,
            'text' => Question::getTextById($_id),
            'answer' => $question['answer']
          ];
        }
      } else {
        foreach ($entry['application']['questions'] as $questionId) {
          $answer = '';
          $answers = StudentModel::getAnswers($studentId);
          $answers = arrayToHashByKey($answers, '_id');
          if (isset($answers[$questionId.''])) {
            $answer = $answers[$questionId.'']['answer'];
          } else {
            $answer = '';
          }
          $questions[] = [
            '_id' => $questionId,
            'text' => Question::getTextById($questionId),
            'answer' => $answer
          ];
        }
      }

      self::render('jobs/applications/apply', [
        'questions' => $questions,
        'jobtitle' => $entry['title'],
        'companytitle' => $company['name'],
        'jobId' => $jobId,
        'submitted' => $submitted
      ]);
    }

    public static function view(array $restOfRoute) {
      JobController::requireLogin();

      $applicationId = self::getIdFromRoute($restOfRoute);
      if (is_null($applicationId)) return;

      $application = ApplicationStudent::getById($applicationId);

      if (is_null($application)) {
        self::error("nonexistent application");
        self::render('notice');
        return;
      }

      // Only the student who submitted the application and the recruiter
      // associated with the job can view the application.
      $myId = $_SESSION['_id'];
      $studentId = $application->getStudentId();
      $jobId = $application->getJobId();
      $recruiterId = JobModel::getRecruiterId($jobId);
      if ($studentId != $myId && $recruiterId != $myId) {
        self::error("permission denied");
        self::render('notice');
        return;
      }

      // Retrieve data for student.
      $student = StudentModel::getById($studentId, ['name' => 1]);
      $studentName = $student['name'];

      // Retrieve data on the job.
      $job = JobModel::getById($jobId);
      $title = $job['title'];
      $companyId = $job['company'];
      $company = CompanyModel::getById($companyId);

      // Set data from application.
      $profile = $application->getProfile();
      $questions = $application->getQuestions();

      // Add 'text' to questions to show.
      $responses = [];
      foreach ($questions as $question) {
        $_id = $question['_id'];
        $responses[] = [
          '_id' => $_id,
          'text' => Question::getTextById($_id),
          'answer' => $question['answer']
        ];
      }

      self::render('jobs/applications/view', [
        'responses' => toJSON($responses),
        'studentname' => $studentName,
        'jobtitle' => $title,
        'companytitle' => $company['name']
      ]);
      self::render('jobs/student/studentprofile', [
        'profile' => toJSON($profile)
      ]);
    }

    public static function createCustom() {
      global $params;

      $text = $params['text'];
      $recruiterId = $_SESSION['_id'];

      $question = Question::createCustom($text, $recruiterId);

      return $question->getId();
    }

    public static function deleteCustom() {
      global $params;

      $questionId = new MongoId($params['questionId']);
      $jobId = new MongoId($params['jobId']);
      $recruiterId = $_SESSION['_id'];

      $question = Question::getById($questionId);
      if ($question->getVanilla() == true) {
        return;
      }

      $usesCount = $question->getUsesCount();

      // Delete question only if:
      // ('uses' is 0) or ('uses' is 1 and the only use is $jobId).
      if ($usesCount > 1) {
        return;
      }
      if ($usesCount == 1) {
        $use = $question->getUses()[0];
        if ($use != $jobId) {
          return;
        }
      }

      return Question::delete($questionId);
    }

    public static function searchCustom() {
      global $params;

      $search = $params['search'];

      $questions = Question::searchCustom($search);
      $questionData = self::questionArrayToJson($questions);

      return $questionData;
    }

    /**
     * Saves the questionIds from client for $jobId.
     * Returns true if performed.
     */
    private static function save(MongoId $jobId) {
      global $params;

      if (!isset($params['questionIds'])) return false;

      $questionIds = $params['questionIds'];

      // Convert all questionIds to MongoIds.
      foreach ($questionIds as $index => $val) {
        $questionIds[$index] = new MongoId($val);
      }

      // Update job application questions.
      $success = ApplicationJob::createOrUpdate($jobId, $questionIds);

      return true;
    }

    private static function questionArrayToJson(array $questions) {
      $questionData = [];
      foreach ($questions as $question) {
        $data = $question->getData();
        $data['_id'] = (string)$data['_id'];
        $data['text'] = clean($data['text']);
        $questionData[] = $data;
      }
      return json_encode($questionData);
    }

    /**
     * Checks if $jobId is a job, and if not, error.
     */
    private static function checkJobExists(MongoId $jobId) {
      if (!JobModel::exists($jobId)) {
        self::error("nonexistent job");
        self::render('notice');
        return false;
      }
      return true;
    }

    /**
     * Checks if the recruiters owns $jobId, and if not, error.
     */
    private static function ownsJob(MongoId $jobId) {
      $recruiterId = $_SESSION['_id'];
      if (!JobModel::matchJobRecruiter($jobId, $recruiterId)) {
        self::error("permission denied");
        self::render('notice');
        return false;
      }
      return true;
    }
  }
?>