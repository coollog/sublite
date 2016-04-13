<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface StatsControllerAJAXInterface {
    public static function getRecruiterStats();
    public static function getStudentStats();
    public static function getSubletStats();
    public static function getSchoolStats();
    public static function getMessageStats();
    public static function getApplicationStats();
  }

  class StatsControllerAJAX extends StatsController
                            implements StatsControllerAJAXInterface {
    public static function getRecruiterStats() {
      $recruiterArray = [];
      $recruiterColumns = ["email","firstname","lastname","company","datejoined",
        "credits", "postedjob","madecompany","approved"];
      $jobColumns = ["jobname","jobviews","jobclicks","applicants"];
      $recruiterArray[] = implode(',', $recruiterColumns) . ',' . implode(',', $jobColumns);

      $r = RecruiterModel::find();
      $j = JobModel::find();

      $rids = []; // an array of recruiters ids that have posted jobs
      foreach ($j as $job) {
        $rids[] = $job['recruiter']->{'$id'};
      }

      foreach ($r as $recruiter) {
        $info = [];
        $id = $recruiter['_id'];
        $rdoc = RecruiterModel::getById($id);
        if (isset($recruiter['email'])) $info['email'] = $recruiter['email'];
        else $info['email'] = 'no email';
        if (isset($recruiter['firstname'])) $info['firstname'] = $recruiter['firstname'];
        else $info['firstname'] = 'no firstname';
        if (isset($recruiter['lastname'])) $info['lastname'] = $recruiter['lastname'];
        else $info['lastname'] = 'no lastname';
        if (isset($recruiter['company'])) {
          if (MongoID::isValid($recruiter['company'])) { // if the company is a MongoID
            $info['company'] = CompanyModel::getName($recruiter['company']);
          } else {
            $info['company'] = $recruiter['company'];
          }

        }
        else $info['company'] = 'no company';
        $info['dateJoined'] = fdate($recruiter['_id']->getTimestamp());
        if (isset($recruiter['credits'])) $info['credits'] = $recruiter['credits'];
        else $info['credits'] = 0;

        if (in_array($id, $rids)) { // recruiters who have posted at least one job and have a company profile
          $info['postedJob'] = 'YES';
          $info['madeCompany'] = 'YES';
          $info['approved'] = 'YES';
        } else {// recruiters who have not posted a job
          $info['postedJob'] = 'NO';
          if (MongoID::isValid($recruiter['company'])) { // recruiters who have a company profile
            $info['madeCompany'] = 'YES';
            $info['approved'] = 'YES';
          } else { // recruiters who don't have a company profile
            $info['madeCompany'] = 'NO';
            if ($recruiter['approved'] == 'approved') { // recruiters who are approved
              $info['approved'] = 'YES';
            } else { // recruiters who are not approved
              $info['approved'] = 'NO';
            }
          }
        }
        $recruiterArray[] = implode(',', self::quoteStringsInArray($info));
        $jobs = JobModel::getByRecruiter($id); // get jobs for recruiter

        foreach ($jobs as $job) { //make rows under each recruiter for their listed jobs
          $jobInfo = [];
          if (isset($job['title'])) $jobInfo['title'] = $job['title'];
          else 'No Job Title';
          $jobInfo['views'] = $job['stats']['views'];
          $jobInfo['clicks'] = $job['stats']['clicks'];
          $jobInfo['applicants'] = ApplicationModel::countByJob($job['_id'])['total'];
          $recruiterArray[] =
            str_repeat(',', count($recruiterColumns)) .
            implode(',', self::quoteStringsInArray($jobInfo));
        }
      }

      echo toJSON($recruiterArray);
    }

    public static function getStudentStats() {
      // TODO: Convert MStats to be StatsModel static calls.
      global $MStats;
      $studentArray = [];
      $studentArray[] = "email , firstname , lastname , school , confirmed";
      $confirmedEmails = [];

      $confirmed = $MStats->getStudentsConfirmed();
      $all = $MStats->getStudentsAll();

      foreach ($confirmed as $student) {
        $confirmedEmails[] = $student['email'];
      }

      foreach ($all as $student) {
        $info = [];
        if (isset($student['email'])) $info['email'] = $student['email'];
        else $info['email'] = 'No Email';
        if (isset($student['name'])) $name = $student['name'];
        else $name = "NoName NoName"; //twice because we explode to get firstname and lastname
        $info['firstname'] = 'User';
        $info['lastname'] = '';
        $name = explode(' ', $name);
        if ($name[0] != '') {
          $info['firstname'] = $name[0];
          $info['lastname'] = isset($name[1]) ? $name[1] : '';
        }
        if (isset($student['school'])) $info['school'] = $student['school'];
        else $info['school'] = 'unknown school';
        if (in_array($info['email'], $confirmedEmails)) $info['confirmed'] = 'YES';
        else $info['confirmed'] = 'NO';
        $studentArray[] = implode(',', self::quoteStringsInArray($info));
      }
      echo toJSON(["studentArray" => $studentArray, "confirmedStudentArray" => $confirmedEmails]);
    }

    public static function getSubletStats() {
      $subletsended2014Array = [];

      $sublets = SubletModel::find(
        [ 'enddate' => [ '$lte' => strtotime('1/1/2015') ] ]);

      $ss = array();
      foreach ($sublets as $s) {
        $id = $s['_id'];
        $student = StudentModel::getById($s['student']);
        if (isset($student['name'])) $name = $student['name'];
        else $name = 'noname';
        if (isset($student['email'])) $email = $student['email'];
        else $email = 'noemail';
        $ss[] = "\"$email\",\"$name\",\"$id\"";
      }
      $subletsended2014Array['subletsended2014'] = $ss;
      echo toJSON($subletsended2014Array);
    }

    public static function getSchoolStats() {
      // TODO: Change $S to use Schools static class calls.
      global $S;
      $unknownschoolsArray = [];

      $domains = [];
      $students = StudentModel::getAll();
      foreach ($students as $student) {
        $email = $student['email'];
        if (!$S->hasSchoolOf($email)) {
          $domain = $S->getDomain($email);
          if (!in_array($domain, $domains)) {
            $domains[] = $domain;
          }
        }
      }
      $count = count($domains);
      $unknownschoolsArray['domains'] = $domains;
      echo toJSON($unknownschoolsArray);
    }

    public static function getMessageStats() {
      $msgs = MessageModel::getAll();
      $getMessageParticipantsArray = [];

      $plist = array();

      foreach ($msgs as $m) {
        $replies = $m['replies'];
        if (count($replies) == 0) continue;

        $participants = $m['participants'];

        foreach ($participants as $p) {
          $name = MessageController::getName($p);
          $email = MessageController::getEmail($p);
          $type = MessageController::getType($p);

          if (isset($plist[$email])) {
            $plist[$email]['count']++;
          } else {
            $plist[$email] = array(
              'name' => $name,
              'count' => 1,
              'type' => $type
            );
          }
        }

      }
      $messages = [];
      foreach ($plist as $email => $data) {
        $type = $data['type'];
        $name = $data['name'];
        $count = $data['count'];
        $messages[] = "$email ($type) - $name - sent $count";
      }

      echo toJSON($messages);
    }

    public static function getApplicationStats() {
      $applicationsArray = [];
      $applicationsArray[] = "jobname, recruiteremail, total, " .
                             "unclaimed, review, rejected, accepted, reported";
      $all = JobModel::getAll(); // get all jobs
      foreach ($all as $job) {
        $info = [];
        // Get Job Name
        $info['jobName'] = isset($job['title']) ? $job['title'] : "No Title";
        // Get Recruiter Email
        $info['recruiterEmail'] = "";
        $recruiterId = isset($job['recruiter']) ? $job['recruiter'] : null;
        if ($recruiterId != null) {
          $info['recruiterEmail'] = RecruiterModel::getEmail($recruiterId);
        } else {
          $info['recruiterEmail'] = "No Email";
        }
        // Get Applications data
        $applicationCountData = ApplicationModel::countByJob($job['_id']);
        $info['totalApplicants'] = $applicationCountData['total'];
        $info['unclaimedApplicants'] = $applicationCountData['unclaimed'];
        $info['reviewApplicants'] = $applicationCountData['review'];
        $info['rejectedApplicants'] = $applicationCountData['rejected'];
        $info['acceptedApplicants'] = $applicationCountData['accepted'];
        $info['reportedApplicants'] = $applicationCountData['reported'];

        $applicationsArray[] = implode(',', self::quoteStringsInArray($info));
      }
      echo toJSON($applicationsArray);
    }

  }

?>
