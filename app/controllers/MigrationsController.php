<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class MigrationsController extends Controller {
    function migrate() {
      global $MApp;

      // Get current counter
      $mlast = $MApp->get('migrations');
      if ($mlast == NULL) $mlast = 0;
      else $mlast = $mlast['current'];

      // Do migrations after counter
      $mcur = $mlast;
      $migrations = glob($GLOBALS['dirpre'].'migrations/*.php');
      natsort($migrations);
      foreach ($migrations as $m) {
        if (($mcur = str2int($m)) > $mlast) {
          require_once($m);
          echo "performed migration: $mcur<br />";
        }
      }

      if ($mcur > $mlast) {
        $MApp->save(array('_id' => 'migrations', 'current' => $mcur));
        echo "migrated from $mlast to $mcur";
      } else
        echo "migrations up to date";
    }
  }

  GLOBALvarSet('CMigrations', new MigrationsController());
?>