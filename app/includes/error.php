<?php
  error_reporting(E_ALL & ~E_STRICT);
  ini_set('display_errors', '1');
  ini_set("log_errors", 1);
  ini_set("error_log", $GLOBALS['dirpre']."../errors.log");

  // error handler function
  function errorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
      // This error code is not included in error_reporting
      return;
    }

    function senderror($error) {
      $session = $_SESSION;
      unset($session['pass']);
      $m = array2str(array(
        'errormsg' => $error,
        'session' => array2str($session, " &nbsp; &nbsp; %s = '%s'"),
        'server' => array2str($_SERVER, " &nbsp; &nbsp; %s = '%s'"),
        'request' => array2str($_REQUEST, " &nbsp; &nbsp; %s = '%s'")
      ));

      sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'SubLite Error Report', $m);
      //echo "Error report sent!<br />\n";
    }

    switch ($errno) {
      case E_USER_ERROR:
        $error = "<b>My ERROR</b> [$errno] $errstr<br />\n
                  Fatal error on line $errline in file $errfile
                  , PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo $error;

        senderror($error);

        echo 'Aborting...<br />\n'; exit(1);
        break;

      default:
        $error = "Unknown error type: [$errno] \"$errstr\" in file \"$errfile\" on line $errline<br />\n";
        Controller::render('500');
        Controller::finish();

        senderror($error);

        //echo 'Aborting...<br />\n';
        exit(1);
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
  }

  /**
  * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
  */
  function check_for_fatal() {
    $error = error_get_last();
    if ( $error["type"] == E_ERROR )
      errorHandler( $error["type"], $error["message"], $error["file"], $error["line"] );
  }

  //register_shutdown_function( "check_for_fatal" );
  // set_error_handler( "errorHandler" );
?>