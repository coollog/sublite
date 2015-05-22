<?php
  $GLOBALS['dirpre'] = '../app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  echo $CSocial->adminapi();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>