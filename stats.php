<?php
  $GLOBALS['dirpre'] = 'employers/';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  $CStats->update();
  $CStats->nojobs();
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>