<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class StatsController extends Controller {
    function update() {
      global $MApp;

      $countCities = isset($_GET['cities']);

      $stats = $MApp->updateStats($countCities);

      echo 'Updated stats! Next time use ?cities to also update cities count (may take a while).<br /><pre>';
      var_dump($stats); echo '</pre>';
    }
    function nojobs() {
      global $MRecruiter, $MJob;

      $r = $MRecruiter->find();
      $j = $MJob->find();

      $rids = array();
      foreach ($j as $job) {
        $rids[] = $job['recruiter']->{'$id'};
      }

      $emails = array();
      $emailswc = array();
      foreach ($r as $recruiter) {
        $id = $recruiter['_id']->{'$id'};
        $rdoc = $MRecruiter->getById($id);
        if (!in_array($id, $rids)) {
          $emails[] = $rdoc['email'];
          if (MongoID::isValid($recruiter['company'])) {
            $emailswc[] = $rdoc['email'];
          }
        }
      }

      echo 'Recruiters who have not posted jobs:<br />
        <textarea style="width:800px; height: 400px;">';
      foreach ($emails as $email) {
        echo "$email\n";
      }
      echo '</textarea>';
      echo '<br />Recruiters who have not posted jobs but have made a company profile:<br />
        <textarea style="width:800px; height: 400px;">';
      foreach ($emailswc as $email) {
        echo "$email\n";
      }
      echo '</textarea>';
    }
    function students() {
      global $MStats;

      $c = $MStats->getStudentsConfirmed();
      $u = $MStats->getStudentsUnConfirmed();

      echo '<br />Confirmed students: '.$c->count().'<br />
        <textarea style="width:800px; height: 200px;">';
      foreach ($c as $student) {
        $email = $student['email'];
        echo "$email\n";
      }
      echo '</textarea>';
      echo '<br />Unconfirmed students: '.$u->count().'<br />
        <textarea style="width:800px; height: 200px;">';
      foreach ($u as $student) {
        $email = $student['email'];
        echo "$email\n";
      }
      echo '</textarea>';
    }
    function missingrecruiter() {
      global $MStats;

      $mr = $MStats->getJobsMissingRecruiter();
      echo '<br />Jobs nonexistent recruiter: '.count($mr).'<br />
        <textarea style="width:800px; height: 200px;">';
      foreach ($mr as $job) {
        $id = $job['_id'];
        $company = $job['company'];
        $recruiter = $job['recruiter'];
        echo "$id - c: $company, r: $recruiter\n";
      }
      echo '</textarea>';

    }
    function recruiterbydate() {
      global $MRecruiter, $MCompany;

      $recruiters = $MRecruiter->find()->sort(array('_id'=>-1));

      $rs = array();
      foreach ($recruiters as $r) {
        $email = $r['email'];
        $company = $r['company'];
        if (MongoId::isValid($company))
          $company = $MCompany->getName($company);
        $date = fdate($r['_id']->getTimestamp());
        $rs[] = "\"$email\",\"$company\",\"$date\"";
      }

      echo '<br />Recruiters with date of joining:<br />
        <textarea style="width:800px; height: 200px;">';
      foreach ($rs as $r) {
        echo "$r\n";
      }
      echo '</textarea>';
    }
    function subletsended2014() {
      global $MSublet, $MStudent;

      $sublets = $MSublet->find(array('enddate' => array('$lte' => strtotime('1/1/2015'))));

      $ss = array();
      foreach ($sublets as $s) {
        $id = $s['_id'];
        $student = $MStudent->getById($s['student']);
        $name = $student['name'];
        $email = $student['email'];
        $ss[] = "\"$email\",\"$name\",\"$id\"";
      }

      echo '<br />Sublets with end dates before 1/1/2015:<br />
        <textarea style="width:800px; height: 200px;">';
      foreach ($ss as $s) {
        echo "$s\n";
      }
      echo '</textarea>';
    }
    function unknownschools() {
      global $MStudent, $S;

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

      echo "<br />Unknown Schools ($count): <br />
        <textarea style=\"width:800px; height: 200px;\">";
      foreach ($domains as $d) {
        echo "$d\n";
      }
      echo '</textarea>';
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
          if ($time != '_id' and !isset($search['type'])) {
            unset($entry[$time]);
            $MApp->save($entry);
            continue;
          }
          if ($time == '_id' or !isset($search['type']) or $search['type'] != 'sublets') continue;

          if (!isset($search['city'])) {
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

      $this->render('graph', $data);
    }
  }

  $CStats = new StatsController();
?>