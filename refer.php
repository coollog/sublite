<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CStudent->sendReferral();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>