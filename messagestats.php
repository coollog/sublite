<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CStats->requireLogin();
  $CStats->messages();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>