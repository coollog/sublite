<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CJob->requireLogin();
  $CStats->update();
  $CStats->nojobs();
  $CStats->students();
  $CStats->missingrecruiter();
  $CStats->recruiterbydate();
  // $CStats->subletsended2014();
  // $CStats->unknownschools();
  $CStats->cumulative();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>