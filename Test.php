<?php
  require_once('includes/header.php');
  if (isset($_POST['register'])) {
    $CRecruiter->register();
  } else {
    $CRecruiter->render('register');
  }
?>