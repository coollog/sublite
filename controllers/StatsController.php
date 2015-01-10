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
  }

  $CStats = new StatsController();

?>