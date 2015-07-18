<?php
  $GLOBALS['dirpre'] = '../app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  echo $CSocial->api();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>