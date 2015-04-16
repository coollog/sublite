<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $MJob->incrementApply($_GET['id']);
  if(filter_var($_GET['url'], FILTER_VALIDATE_EMAIL)) {
    header("Location: mailto:" . $_GET['url']);
  }
  else {
    header("Location: " . $_GET['url']);
  }
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>
