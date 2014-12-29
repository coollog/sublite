<?php
  $dirpre = '';
  require_once($dirpre.'includes/header.php');
  $CRecruiter->register();
  $CRecruiter->login();
  require_once($dirpre.'includes/footer.php');
?>