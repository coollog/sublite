<?php
  global $params, $valid;

  abstract class Controller {
    function validate($test, &$var, $msg) {
      global $valid;
      if (!$valid) return;
      if (!$test) {
        $valid = false;
        $var = $msg;
      }
    }
    function isValid() {global $valid; return $valid;}
    function startValidations() {global $valid; $valid = true;}

    function render($view, $vars = false) {
      require_once('includes/htmlheader.php');

      // Actual view here
      global $viewVars;
      if ($vars === false) $viewVars = array();
      else $viewVars = $vars;
      require_once("views/$view.php");

      require_once('includes/htmlfooter.php'); 
      require_once('includes/footer.php');
    }
    function redirect($page, $params = NULL) {
      if ($params == NULL)
        header("Location: /$page.php");
      else {
        $query = http_build_query($params);
        header("Location: /$page.php?$query");
      }
      die();
    }
  }

  global $params;
  $params = $_POST;

  // REFACTOR ALL LOGIN/SESSION HANDLING CODE
  session_start();
?>