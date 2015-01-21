<?php
  $GLOBALS['dirpre'] = 'employers/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CMigrations->migrate();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>