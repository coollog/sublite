<?php
  require_once('includes/header.php');
  // PROB THINK OF ANOTHER WAY OF REFACTORING THE FOLLOWING CODE
  if (isset($_SESSION['email'])) echo 'yay!';
  else echo 'nay...';
  require_once('includes/footer.php');
?>