<?php
  require_once('ProcessSearch.php');

  require_once('Decider.php');
  class DeciderStudent extends Decider {
    public static function decide($email) {
      $studentPreferences = ProcessSearch::processByStudent($email);

      return $studentPreferences;
    }
  }
?>