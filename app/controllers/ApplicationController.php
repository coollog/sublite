<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Question.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationJob.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationStudent.php');

  interface ApplicationControllerInterface {
    public static function edit(array $restOfRoute);
    public static function apply(array $restOfRoute);
    public static function createCustom();
    public static function deleteCustom();
    public static function searchCustom();
  }

  class ApplicationController extends Controller
                              implements ApplicationControllerInterface {
    public static function edit(array $restOfRoute) {
      global $CRecruiter; $CRecruiter->requireLogin();
      global $params;

      if (!isset($restOfRoute[0]) || !MongoId::isValid($restOfRoute[0])) {
        self::error("invalid access");
        self::render('notice');
        return;
      }

      $jobId = new MongoId($restOfRoute[0]);
      $recruiterId = $_SESSION['_id'];

      // Make sure job exists.
      if (!JobModel::exists($jobId)) {
        self::error("nonexistent job");
        self::render('notice');
        return;
      }

      // Make sure recruiter has permission to edit the job.
      if (!JobModel::matchJobRecruiter($jobId, $recruiterId)) {
        self::error("permission denied");
        self::render('notice');
        return;
      }

      // Process saving of questions.
      if (self::save($jobId)) {
        return;
      }

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
      global $params, $MJob, $MCompany, $CStudent;

      $CStudent->requireLogin();

      if (!isset($restOfRoute[0]) || !MongoId::isValid($restOfRoute[0])) {
        self::error("invalid access");
        self::render('notice');
        return;
      }

      $jobId = new MongoId($restOfRoute[0]);
      $studentId = $_SESSION['_id'];
      $application = ApplicationJob::get($jobId);

      // Make sure job exists.
      if (!JobModel::exists($jobId)) {
        self::error("nonexistent job");
        self::render('notice');
        return;
      }

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
        $application = ApplicationStudent::submitNew($jobId, $studentId, $answers);
        $submitted = true;
      }

      $entry = JobModel::getById($jobId);
      $companyId = $entry['company'];
      $company = CompanyModel::getById($companyId);

      $questions = array();

      if ($submitted) {
        foreach ($application->getQuestions() as $answer) {
          $_id = $answer['_id'];
          $questions[] = [
            '_id' => $_id,
            'text' => Question::getTextById($_id),
            'answer' => $answer['answer']
          ];
        }
      } else if (ApplicationModel::applicationExists($jobId, $studentId)) {
        $application = new ApplicationStudent(
          ApplicationModel::getApplication($jobId, $studentId));
        $submitted = ApplicationModel::checkApplicationSubmitted($application->getId());

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
  }
?>