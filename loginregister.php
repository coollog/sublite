<?php
  $GLOBALS['dirpre'] = '';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CRecruiter->register();
  $CRecruiter->login();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>