<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class StatsController extends Controller {

    function loadStats() {
      $updateArray = self::update();
      $recruiterArray = self::recruiters();
      $studentArray = self::students();
      $confirmedStudentArray = self::confirmedStudents();
      $missingRecruiterArray = self::missingrecruiter();
      $subletsended2014Array = self::subletsended2014();
      $unknownSchoolsArray = self::unknownschools();
      $cumulativeArray = self::cumulative();
      $getMessageParticipantsArray = self::getMessageParticipants();
      $applicationsArray = self::applications();

      $toRender = [
        "updateArray" => $updateArray,
        "recruiterArray" => $recruiterArray,
        "studentArray" => $studentArray,
        "confirmedStudentArray" => $confirmedStudentArray,
        "missingRecruiterArray" => $missingRecruiterArray,
        "subletsended2014Array" => $subletsended2014Array,
        "unknownSchoolsArray" => $unknownSchoolsArray,
        "cumulativeArray" => $cumulativeArray,
        "getMessageParticipantsArray" => $getMessageParticipantsArray,
        "applicationsArray" => $applicationsArray
      ];
      self::render('/stats/stats', $toRender);
    }

    /*
    * Returns a view with updated stats
    */
    function update() {
      global $MApp, $MStats;
      $updateArray = ["cities" => null];

      $stats = $MApp->updateStats();
      $updateArray['stats'] = $stats;
      if (isset($_GET['cities'])) {
        $cities = $MStats->getCities();
        asort($cities);
        $updateArray['cities'] = $cities;
      }
      //echo 'Updated stats! Next time use ?cities to also update cities count (may take a while).<br /><pre>';
      return $updateArray;
    }

    /*
    * Returns a view that has lists of emails of recruiters without companies and without jobs
    */
    function recruiters() {
      global $MRecruiter, $MJob, $MCompany, $MApp;
      $recruiterArray = [];
      $recruiterColumns = ["email","firstname","lastname","company","datejoined","postedjob","madecompany","approved"];
      $jobColumns = ["jobname","jobviews","jobclicks","applicants"];
      $recruiterArray[] = implode(',', $recruiterColumns) . ',' . implode(',', $jobColumns);


      $r = $MRecruiter->find();
      $j = $MJob->find();

      $rids = array(); // an array of recruiters ids that have posted jobs
      foreach ($j as $job) {
        $rids[] = $job['recruiter']->{'$id'};
      }

      foreach ($r as $recruiter) {
        $info = [];
        $id = $recruiter['_id'];
        $rdoc = $MRecruiter->getById($id);
        if (isset($recruiter['email'])) $info['email'] = $recruiter['email'];
        else $info['email'] = 'no email';
        if (isset($recruiter['firstname'])) $info['firstname'] = $recruiter['firstname'];
        else $info['firstname'] = 'no firstname';
        if (isset($recruiter['lastname'])) $info['lastname'] = $recruiter['lastname'];
        else $info['lastname'] = 'no lastname';
        if (isset($recruiter['company'])) {
          if (MongoID::isValid($recruiter['company'])) { // if the company is a MongoID
            $info['company'] = $MCompany->getName($recruiter['company']);
          } else {
            $info['company'] = $recruiter['company'];
          }

        }
        else $info['company'] = 'no company';
        $info['dateJoined'] = fdate($recruiter['_id']->getTimestamp());

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
        $jobs = $MJob->getByRecruiter($id); // get jobs for recruiter

        foreach ($jobs as $job) { //make rows under each recruiter for their listed jobs
          $jobInfo = [];
          if (isset($job['title'])) $jobInfo['title'] = $job['title'];
          else 'No Job Title';
          $jobInfo['views'] = $job['stats']['views'];
          $jobInfo['clicks'] = $job['stats']['clicks'];
          $jobInfo['applicants'] = ApplicationModel::countByJob($job['_id'])['total'];
          $recruiterArray[] = str_repeat(',', count($recruiterColumns)) . implode(',', self::quoteStringsInArray($jobInfo));

        }

      }
      return ["recruiterArray" => $recruiterArray];
    }

    /*
    * Returns a view with students
    */
    function students() {
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
      return ["studentArray" => $studentArray];
    }

    function confirmedStudents() {
      global $MStats;

      $confirmed = $MStats->getStudentsConfirmed();
      $confirmedEmails = [];

      foreach ($confirmed as $student) {
        $confirmedEmails[] = $student['email'];
      }

      return ["confirmedStudentArray" => $confirmedEmails];

    }

    /*
    * Returns recruiters who are missing jobs
    */
    function missingrecruiter() {
      global $MStats;
      $missingRecruiterArray = [];

      $mr = $MStats->getJobsMissingRecruiter();

      $missingRecruiterArray["missingRecruiters"] = $mr;
      return $missingRecruiterArray;

    }

    function subletsended2014() {
      global $MSublet, $MStudent;
      $subletsended2014Array = [];

      $sublets = $MSublet->find(array('enddate' => array('$lte' => strtotime('1/1/2015'))));

      $ss = array();
      foreach ($sublets as $s) {
        $id = $s['_id'];
        $student = $MStudent->getById($s['student']);
        if (isset($student['name'])) $name = $student['name'];
        else $name = 'noname';
        if (isset($student['email'])) $email = $student['email'];
        else $email = 'noemail';
        $ss[] = "\"$email\",\"$name\",\"$id\"";
      }
      $subletsended2014Array['subletsended2014'] = $ss;
      return $subletsended2014Array;
    }
    function unknownschools() {
      global $MStudent, $S;
      $unknownschoolsArray = [];

      $domains = array();
      $students = $MStudent->getAll();
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
      return $unknownschoolsArray;
    }
    /*
    * Gives cumulative stats i.e. total number of jobs clicks, total number of jobs views, etc.
    */
    function cumulative() {
      global $MJob;
      $cumulativeArray = [];

      $views = $clicks = $claimedApps = $unclaimedApps = 0;
      $jobs = $MJob->getAll();
      foreach ($jobs as $job) {
        $views += $job['stats']['views'];
        $clicks += $job['stats']['clicks'];
        $claimedApps += count(ApplicationModel::getClaimed($job['_id']));
        $unclaimedApps += count(ApplicationModel::getUnclaimed($job['_id']));
      }
      $cumulativeArray['cumulativeviews'] = $views;
      $cumulativeArray['cumulativeclicks'] = $clicks;
      // Application stats
      $cumulativeArray['claimedApplications'] = $claimedApps;
      $cumulativeArray['unclaimedApplications'] = $unclaimedApps;
      return $cumulativeArray;
    }
    function graph() {
      global $MStudent;

      // All students by month
      $studentsdata = array();
      $students = $MStudent->getAll();
      foreach ($students as $student) {
        $time = (int)($student['_id']->getTimestamp()/3600/24/30.2);
        if (!isset($studentsdata[$time])) $studentsdata[$time] = 1;
        else $studentsdata[$time] ++;
      }
      ksort($studentsdata);

      // Students by day
      $studentsdaydata = array();
      $students = $MStudent->getAllwTime();
      foreach ($students as $student) {
        $time = (int)($student['time']/3600/24);
        if (!isset($studentsdaydata[$time])) $studentsdaydata[$time] = 1;
        else $studentsdaydata[$time] ++;
      }
      array_splice($studentsdaydata, 0, -100);
      ksort($studentsdaydata);

      // Messages
      global $MMessage;
      $msgdata = array();
      $msgs = $MMessage->getAll();
      foreach ($msgs as $msg) {
        foreach ($msg['replies'] as $reply) {
          $time = (int)($reply['time']/3600/24);
          if (!isset($msgdata[$time])) $msgdata[$time] = 1;
          else $msgdata[$time] ++;
        }
      }
      ksort($msgdata);

      $data = array(
        'students' => $studentsdata,
        'studentsday' => $studentsdaydata,
        'msgs' => $msgdata
      );

      if (isset($_GET['cities'])) {
        // Searches
        global $MApp;

        $searchdata = array();

        $entry = $MApp->getSearches();
        $searches = array_slice($entry, -$_GET['cities'], NULL, true);

        foreach ($searches as $time => $search) {
          //echo "$time=>";
          if ($time != '_id' and !isset($search['type'])) {
            unset($entry[$time]);
            $MApp->save($entry);
            continue;
          }
          if ($time == '_id' or !isset($search['type']) or $search['type'] != 'sublets') continue;

          if (!isset($search['city']) or $search['city'] == null) {
            $location = $search['data']['location'];
            $city = Geocode::getCity($location);
            // Save cities so don't need to recurl in the future
            $entry[$time]['city'] = $city;
            $MApp->save($entry);
          } else
            $city = $search['city'];

          if (!isset($searchdata[$city])) $searchdata[$city] = 1;
          else $searchdata[$city] ++;
        }
        $data['searchcities'] = $searchdata;
      }

      $this->render('stats/graph', $data);
    }

    function requireLogin() {
      global $CJob;
      $CJob->requireLogin();
      if (!checkAdmin())
        die('permission denied');
    }
    function messages() {
      global $MMessage, $CMessage;
      $msgs = $MMessage->getAll();

      $mlist = array();
      foreach ($msgs as $m) {
        $replies = $m['replies'];
        if (count($replies) == 0) continue;

        $lasttime = $replies[count($replies) - 1]['time'];
        $participants = $m['participants'];
        $plist = array();
        foreach ($participants as $p) {
          $plist[] = array(
            'name' => $CMessage->getName($p),
            'email' => $CMessage->getEmail($p)
          );
        }

        $rlist = array();
        foreach ($replies as $r) {
          $from = $r['from'];
          $to = array();
          foreach ($participants as $p) {
            if ($p != $from) {
              $to[] = array(
                'name' => $CMessage->getName($p),
                'email' => $CMessage->getEmail($p)
              );
            }
          }
          $time = $r['time'];
          $read = $r['read'];
          $msg = $r['msg'];
          $name = $CMessage->getName($from);
          $email = $CMessage->getEmail($from);
          $time = date("r", $time);
          $rlist[] = array(
            'name' => $name,
            'email' => $email,
            'time' => $time,
            'read' => $read,
            'msg' => $msg,
            'to' => $to
          );
        }
        $rlist = array_reverse($rlist);
        $mlist[$lasttime] = array(
          'participants' => $plist,
          'lasttime' => date("r", $lasttime),
          'replies' => $rlist
        );
      }
      ksort($mlist);
      $mlist = array_reverse($mlist);

      $this->render('stats/messagestats', array('mlist' => $mlist));
    }
    function getMessageParticipants() {
      global $MMessage, $CMessage;
      $msgs = $MMessage->getAll();
      $getMessageParticipantsArray = [];

      $plist = array();

      foreach ($msgs as $m) {
        $replies = $m['replies'];
        if (count($replies) == 0) continue;

        $participants = $m['participants'];

        foreach ($participants as $p) {
          $name = $CMessage->getName($p);
          $email = $CMessage->getEmail($p);
          $type = $CMessage->getType($p);

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

      foreach ($plist as $email => $data) {
        $type = $data['type'];
        $name = $data['name'];
        $count = $data['count'];
      }
      $getMessageParticipantsArray['messageparticipants'] = $plist;
      return $getMessageParticipantsArray;
    }
    function applications() {
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
      return ["applicationsArray" => $applicationsArray];
    }

    /*
    * Takes an array full of strings, returns an array where each string has been wrapped in double quotes
    */
    private static function quoteStringsInArray($array) {
      $quotedArray = [];
      foreach ($array as $key=>$str) {
        $quotedArray[$key] = '"'.trim($str,'"').'"';
      }
      return $quotedArray;
    }
  }


  GLOBALvarSet('CStats', new StatsController());
?>
