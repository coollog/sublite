<?php
  $dirpre = '';
  require_once($dirpre.'includes/header.php');
  $CMigrations->migrate();
  require_once($dirpre.'includes/footer.php');
?>