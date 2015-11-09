<?php
  $GLOBALS['dirpre'] = isset($GLOBALS['dirpre']) ? $GLOBALS['dirpre'] : '../';
  require_once($GLOBALS['dirpre'].'includes/header.php');

  Model::$test = true;

  require_once($GLOBALS['dirpre'].'tests/models/StudentModelTest.php');
  StudentModelTest::run();

  require_once($GLOBALS['dirpre'].'tests/controllers/modules/application/QuestionTest.php');
  QuestionTest::run();

  require_once($GLOBALS['dirpre'].'tests/controllers/modules/application/StudentProfileTest.php');
  StudentProfileTest::run();

  require_once($GLOBALS['dirpre'].'tests/controllers/modules/application/ApplicationTest.php');
  ApplicationTest::run();
?>