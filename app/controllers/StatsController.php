<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class StatsController extends Controller {

    function loadStats() {
      $updateArray = self::update(); // contains arrays 'stats' and 'cities'
      $nojobsArray = self::nojobs(); // contains arrays 'recruiterEmails', 'recruiterEmailsWC', and 'recruiterEmailsWJ'
      $studentsArray = self::students(); // contains arrays 'studentConfirmedEmails', 'studentsUnconfirmedEmails', 'allStudents'
      $missingRecruiterArray = self::missingrecruiter(); //contains array 'missingRecruiters'
      $recruiterByDateArray = self::recruiterbydate(); //contains array 'recruiterByDate'
      $subletsended2014Array = self::subletsended2014(); // contains array 'subletsended2014'
      $unknownSchoolsArray = self::unknownschools(); // contains array 'domains'
      $cumulativeArray = self::cumulative(); // contains arrays 'cumulativeviews', 'cumulativeclicks'
      $getMessageParticipantsArray = self::getMessageParticipants(); // contains array 'messageparticipants'

      $toRender = [
        "updateArray" => $updateArray,
        "nojobsArray" => $nojobsArray,
        "studentsArray" => $studentsArray,
        "missingRecruiterArray" => $missingRecruiterArray,
        "recruiterByDateArray" => $recruiterByDateArray,
        "subletsended2014Array" => $subletsended2014Array,
        "unknownSchoolsArray" => $unknownSchoolsArray,
        "cumulativeArray" => $cumulativeArray,
        "getMessageParticipantsArray" => $getMessageParticipantsArray
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
    function nojobs() {
      global $MRecruiter, $MJob, $MCompany;
      $nojobsArray = [];

      $r = $MRecruiter->find();
      $j = $MJob->find();

      $rids = array();
      foreach ($j as $job) {
        $rids[] = $job['recruiter']->{'$id'};
      }

      $emails = [];
      $emailswc = [];
      $emailswj = [];
      $onlyApproved = [];
      foreach ($r as $recruiter) {
        $id = $recruiter['_id']->{'$id'};
        $rdoc = $MRecruiter->getById(new MongoId($id));
        if (in_array($id, $rids)) {
          $emailswj[] = $rdoc; // recruiters who have posted at least one job and have a company profile
        } else {
          $emails[] = $rdoc; // recruiters who have not posted a job
          if (MongoID::isValid($recruiter['company'])) { // recruiters who have a company profile
            $emailswc[] = $rdoc; 
          } else { // recruiters who don't have a company profile
            if ($recruiter['approved'] == 'approved') { // recruiters who are approved
              $onlyApproved[] = $rdoc;
            } else { // recruiters who are not approved

            }
          }
        }
      }
      $nojobsArray['recruiterEmails'] = $emails;
      $nojobsArray['recruiterEmailsWC'] = $emailswc;
      $nojobsArray['recruiterEmailsWJ'] = $emailswj;
      $nojobsArray['recruiterEmailsOnlyApproved'] = $onlyApproved;

      return $nojobsArray;
    }

    /*
    * Returns a view with students
    */
    function students() {
      global $MStats;
      $studentsArray = [];

      $c = $MStats->getStudentsConfirmed();
      $u = $MStats->getStudentsUnConfirmed();
      $all = $MStats->getStudentsAll();

      $confirmedEmails = [];
      $unconfirmedEmails = [];
      $allStudents = [];

      foreach ($c as $student) {
        $confirmedEmails[] = $student['email'];
      }

      foreach ($u as $student) {
        $unconfirmedEmails[] = $student['email'];
      }

      foreach ($all as $student) {
        $email = $student['email'];
        $firstname = 'User';
        $lastname = '';
        if (isset($student['name'])) {
          $name = explode(' ', $student['name']);
          if ($name[0] != '') {
            $firstname = $name[0];
            $lastname = isset($name[1]) ? $name[1] : '';
          }
        }
        $allStudents[] = ["firstname" => $firstname, "lastname" => $lastname, "email" => $email];
      }
      $studentsArray['studentsConfirmedEmails'] = $confirmedEmails;
      $studentsArray['studentsUnconfirmedEmails'] = $unconfirmedEmails;
      $studentsArray['allStudents'] = $allStudents;
      return $studentsArray;
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
    function recruiterbydate() {
      global $MRecruiter, $MCompany;
      $recruiterByDateArray = [];

      $recruiters = $MRecruiter->find()->sort(array('_id'=>-1));

      $rs = array();
      foreach ($recruiters as $r) {
        $email = $r['email'];
        $firstname = $r['firstname'];
        $lastname = $r['lastname'];
        $company = $r['company'];
        if (MongoId::isValid($company))
          $company = $MCompany->getName($company);
        $date = fdate($r['_id']->getTimestamp());
        $rs[] = "\"$email\",\"$firstname\",\"$lastname\",\"$company\",\"$date\"";
      }
      $recruiterByDateArray["recruiterByDate"] = $rs;
      return $recruiterByDateArray;
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
    function cumulative() {
      global $MJob;
      $cumulativeArray = [];

      $views = 0; $clicks = 0;
      $jobs = $MJob->getAll();
      foreach ($jobs as $job) {
        $views += $job['stats']['views'];
        $clicks += $job['stats']['clicks'];
      }
      $cumulativeArray['cumulativeviews'] = $views;
      $cumulativeArray['cumulativeclicks'] = $clicks;
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
            $city = getCity($location);
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
            $plist[$email]['count'] ++;
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
  }

  GLOBALvarSet('CStats', new StatsController());
?>
