<?php
  global $params, $valid;

  abstract class Controller {
    function validate($test, &$var, $msg) {
      global valid;
      if (!valid) return;
      if (!$test) {
        $valid = false;
        $var = $msg;
      }
    }
    function valid() {global $valid; return $valid;}
    function startValidations() {global $valid; $valid = true;}

    function render($view) {
      require_once('includes/htmlheader.php');

      // Actual view here
      require_once("views/$view");

      require_once('includes/htmlfooter.php'); 
      require_once('includes/footer.php');
    }

    function __construct() {
      global params;
      $params = $_POST;
      session_start();
    }
  }
?>