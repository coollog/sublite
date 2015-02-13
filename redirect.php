<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $MJob->incrementApply($_GET['id']);
  header("Location: " . $_GET['url']);
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>
