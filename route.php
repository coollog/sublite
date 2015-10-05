<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($dirpre.'includes/header.php');

  // Register functions to call.
  Router::register('index', function() {
    GLOBALvarGet('CStudent')->index();
  });

  // Map route to registered functions.
  Router::route('/index', 'index');

  Router::run();

  require_once($dirpre.'includes/footer.php');
?>