<?php
  $GLOBALS['dirpre'] = '../employers/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CJob->search();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>