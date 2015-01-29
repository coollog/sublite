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
        }
        if (MongoID::isValid($recruiter['company'])) {
          $emailswc[] = $rdoc['email'];
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
  }

  $CStats = new StatsController();

?>