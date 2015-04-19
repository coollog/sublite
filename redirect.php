<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $MJob->incrementApply($_GET['id']);
  if(filter_var($_GET['url'], FILTER_VALIDATE_EMAIL)) {
    header("Location: mailto:" . $_GET['url']);
  }
  else {
    $link = $_GET['url'];
    if (!preg_match('`^(https?:\/\/)`', $_GET['url'])) $link = "http://" . $link;
    header("Location: " . $link);
  }
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>
