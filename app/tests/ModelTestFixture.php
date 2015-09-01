<?php
  $GLOBALS['dirpre'] = '../';
  require_once($GLOBALS['dirpre'].'includes/header.php');

  require_once($GLOBALS['dirpre'].'tests/TestFixture.php');

  require_once($GLOBALS['dirpre'].'tests/models/StudentModelTest.php');
  StudentModelTest::run();
?>