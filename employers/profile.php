<?php
  $GLOBALS['dirpre'] = '';
  require_once($GLOBALS['dirpre'].'includes/header.php');
  // PROB THINK OF ANOTHER WAY OF REFACTORING THE FOLLOWING CODE
  if (isset($_SESSION['email'])) echo 'yay!';
  else echo 'nay...';
  require_once($GLOBALS['dirpre'].'includes/footer.php');
?>