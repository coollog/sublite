<?php
  $GLOBALS['dirpre'] = '../app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CRecruiter->home();
  $CJob->manage();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>