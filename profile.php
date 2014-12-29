<?php
  $dirpre = '';
  require_once($dirpre.'includes/header.php');
  // PROB THINK OF ANOTHER WAY OF REFACTORING THE FOLLOWING CODE
  if (isset($_SESSION['email'])) echo 'yay!';
  else echo 'nay...';
  require_once($dirpre.'includes/footer.php');
?>