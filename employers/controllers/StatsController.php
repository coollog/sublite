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
      foreach ($r as $recruiter) {
        $id = $recruiter['_id']->{'$id'};
        if (!in_array($id, $rids)) {
          $rdoc = $MRecruiter->getById($id);
          $emails[] = $rdoc['email'];
        }
      }

      echo 'Recruiters who have not posted jobs:<br />
        <textarea style="width:400px; height: 400px;">';
      foreach ($emails as $email) {
        echo "$email\n";
      }
      echo '</textarea>';
    }
  }

  $CStats = new StatsController();

?>