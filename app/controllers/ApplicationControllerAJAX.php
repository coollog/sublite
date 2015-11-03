<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Question.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationStudent.php');

  interface ApplicationControllerAJAXInterface {
    /**
     * Calls made on viewing applicants interface.
     */
    public static function applicantsTabUnclaimed();
    public static function applicantsTabClaimed();
    public static function applicantsTabCredits();
    public static function moveApplications();
    public static function claimApplications();
    public static function report();

    /**
     * Calls made while editing an application.
     */
    public static function createCustom();
    public static function deleteCustom();
    public static function searchCustom();
  }

  class ApplicationControllerAJAX
    extends ApplicationController
    implements ApplicationControllerAJAXInterface {

    public static function applicantsTabUnclaimed() {
      RecruiterController::requireLogin();

      $jobId = new MongoId($_POST['jobId']);

      // Make sure job exists.
      // Make sure recruiter owns the job.
      if (!self::checkJobExists($jobId)) return;
      if (!self::ownsJob($jobId)) return;

      // Get the counts.
      $countsHash = self::getCountsHash($jobId);

      // Get the insights.
      $schools = self::getSchoolsForUnclaimed($jobId);
      $num = count($schools);
      if ($num > 4) {
        $schools = array_rand($schools, $num);
      }

      $data = array_merge($countsHash, [
        'schools' => $schools
      ]);
      echo toJSON($data);
    }

    public static function applicantsTabClaimed() {
      RecruiterController::requireLogin();

      $jobId = new MongoId($_POST['jobId']);

      // Make sure job exists.
      // Make sure recruiter owns the job.
      if (!self::checkJobExists($jobId)) return;
      if (!self::ownsJob($jobId)) return;

      // Get the claimed applications.
      $claimed = ApplicationStudent::getClaimedByJob($jobId);

      // Retrieve just _id, name, school, and date for each.
      $applicationList = [];
      $count = 0;
      foreach ($claimed as $status => &$claimedList) {
        $applicationList[$status] = [];
        $count += count($claimedList);

        foreach ($claimedList as $application) {
          $applicationId = $application->getId();
          $time = $applicationId->getTimestamp();
          $date = fdatelong($time);

          $studentId = $application->getStudentId();
          $student = StudentModel::getByIdMinimal($studentId);

          $applicationList[$status][] = [
            '_id' => $applicationId,
            'name' => $student['name'],
            'school' => $student['school'],
            'date' => $date
          ];
        }
      }

      // Get the counts.
      $countsHash = self::getCountsHash($jobId);

      $data = array_merge($applicationList, $countsHash);
      echo toJSON($data);
    }

    public static function applicantsTabCredits() {
      RecruiterController::requireLogin();

      $jobId = new MongoId($_POST['jobId']);

      // Make sure job exists.
      // Make sure recruiter owns the job.
      if (!self::checkJobExists($jobId)) return;
      if (!self::ownsJob($jobId)) return;

      $countsHash = self::getCountsHash($jobId);

      // Get cards.
      $recruiterId = $_SESSION['_id'];
      $customerId = RecruiterModel::getCustomerId($recruiterId);
      $cards = StripeBilling::getCards($customerId);

      $data = array_merge([
        'cards' => $cards
      ], $countsHash);
      echo toJSON($data);
    }

    public static function moveApplications() {
      RecruiterController::requireLogin();

      global $params;

      $selected = MongoIdArray($params['selected']);
      $to = $params['to'];
      $recruiterId = $_SESSION['_id'];

      // Make sure all selected are owned.
      foreach ($selected as $applicationId) {
        if (!ApplicationModel::isOwned($recruiterId, $applicationId)) {
          echo toJSON(['error' => 'permission denied']);
          return;
        }
      }

      // Mark new status.
      foreach ($selected as $applicationId) {
        ApplicationStudent::changeStatus($applicationId, $to);
      }

      return self::ajaxSuccess();
    }

    public static function claimApplications() {
      RecruiterController::requireLogin();

      global $params;

      $jobId = new MongoId($params['jobId']);
      $count = intval($params['count']);
      $recruiterId = $_SESSION['_id'];

      // Subtract away credits.
      $credits = RecruiterModel::getCredits($recruiterId);
      if ($credits < $count) return;
      RecruiterModel::setCredits($recruiterId, $credits - $count);

      ApplicationModel::claim($jobId, $count);

      return self::ajaxSuccess();
    }

    public static function report() {
      RecruiterController::requireLogin();

      global $params;

      $applicationId = new MongoId($params['_id']);
      $recruiterId = $_SESSION['_id'];

      if (!ApplicationModel::isOwned($recruiterId, $applicationId)) {
        return self::ajaxError();
      }

      ApplicationModel::changeStatus($applicationId,
                                     ApplicationStudent::STATUS_REPORTED);

      return self::ajaxSuccess();
    }

    public static function createCustom() {
      RecruiterController::requireLogin();

      global $params;

      $text = $params['text'];
      $recruiterId = $_SESSION['_id'];

      $question = Question::createCustom($text, $recruiterId);

      return $question->getId();
    }

    public static function deleteCustom() {
      RecruiterController::requireLogin();

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
      RecruiterController::requireLogin();

      global $params;

      $search = $params['search'];

      $questions = Question::searchCustom($search);
      $questionData = self::questionArrayToJson($questions);

      return $questionData;
    }

    private static function getSchoolsForUnclaimed(MongoId $jobId) {
      global $S;
      $schools = [];
      $unclaimed = ApplicationStudent::getUnclaimedByJob($jobId);
      foreach ($unclaimed as $application) {
        $studentId = $application->getStudentId();
        $student = StudentModel::getById($studentId, ['email' => 1]);
        $email = $student['email'];
        $school = $S->nameOf($email);
        $schools[] = $school;
      }
      return $schools;
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