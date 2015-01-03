<?php
  $GLOBALS['dirpre'] = '../employers/devq/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CJob->search();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>