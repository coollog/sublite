<?php
  $GLOBALS['dirpre'] = '';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CMigrations->migrate();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>