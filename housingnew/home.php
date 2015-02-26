<?php
  $GLOBALS['dirpre'] = '../app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CStudent->home();
  $CSublet->manage();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>