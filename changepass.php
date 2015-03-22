<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CStudent->changePass();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>