<?php
  global $params, $valid, $notice;

  interface ControllerInterface {
    /**
     * Initialization includes:
     * 1) Rendering the buffer if it exists.
     */
    public static function init();

    /**
     * Used to set which meta tags to display in htmlheader.
     */
    public static function displayMetatags($type);
  }

  class Controller implements ControllerInterface {
    public static function init() {
      if (isset($_SESSION['view'])) {
        echo $_SESSION['view'];
        unset($_SESSION['view']);
        die();
      }
    }

    public static function displayMetatags($type) {
      self::$metatagType = $type;
    }

    protected static function ajaxSuccess() {
      echo toJSON(['error' => null]);
      return true;
    }

    protected static function ajaxError(
      $error = 'ajaxError() called. This should not parse.') {
      echo $error;
      return false;
    }

    /**
     * Retrieves the _id portion of the route.
     * If it's not there, errors and returns null.
     */
    protected static function getIdFromRoute(array $restOfRoute) {
      if (!isset($restOfRoute[0]) || !MongoId::isValid($restOfRoute[0])) {
        self::error("invalid access");
        self::render('notice');
        return null;
      }
      return new MongoId($restOfRoute[0]);
    }

    // Used to define which htmlheader meta tags to use.
    private static $metatagType = null;

    private static $renderQueue = array();


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

    function error($msg) {
      global $error;
      $error = $msg;
    }
    function success($msg) {
      global $success;
      $success = $msg;
    }

    function render($view, $vars = false) {
      self::$renderQueue[] = array($view, $vars);
    }
    function directrender($view, $vars = false) {
      global $viewVars;
      if ($vars === false) $viewVars = array();
      else $viewVars = $vars;

      global $error, $success;
      $viewVars['Error'] = isset($error) ? $error : '';
      $viewVars['Success'] = isset($success) ? $success : '';

      require_once($GLOBALS['dirpre'].'views/View.php');
      self::requireUsingRoutePath("views/$view.php");
    }
    function finish() {
      if (count(self::$renderQueue) == 0) return;

      ob_start();

      global $viewVars;
      $viewVars = ['_metatagType' => self::$metatagType];

      foreach (self::$renderQueue as $pair) {
        $vars = $pair[1];
        if ($vars === false) $vars = array();
        $viewVars = array_merge($viewVars, $vars);
      }
      require_once($GLOBALS['dirpre'].'views/View.php');
      self::requireUsingRoutePath('includes/htmlheader.php');

      foreach (self::$renderQueue as $pair) {
        $view = $pair[0]; $vars = $pair[1];
        self::directrender($view, $vars);
      }

      self::requireUsingRoutePath('includes/htmlfooter.php');

      $view = ob_get_clean();
      $_SESSION['view'] = $view;

      // Reloading the page will prevent duplicate requests upon refresh.
      self::refresh();
    }
    function redirectURL($url) {
      header("Location: $url");
      die();
    }
    function redirect($page, $params = NULL) {
      if ($params == NULL)
        header("Location: $page");
      else {
        $query = http_build_query($params);
        header("Location: $page?$query");
      }
      die();
    }
    function refresh() {
      header('refresh: 0');
      die();
    }

    function sendrequestreport($type, $more=null) {
      $session = $_SESSION;
      unset($session['pass']);
      $content = array(
        'type' => $type,
        'session' => array2str($session, " &nbsp; &nbsp; %s = '%s'"),
        'server' => array2str($_SERVER, " &nbsp; &nbsp; %s = '%s'"),
        'request' => array2str($_REQUEST, " &nbsp; &nbsp; %s = '%s'")
      );
      // if (!is_null($more))
      //   $content['more'] = "<b>results: </b><pre>".var_export($more, true).
      //                      "</pre>";

      $m = array2str($content);
      sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'SubLite Search Report', $m);
    }

    private static function requireUsingRoutePath($relPath) {
      $dirpreOrig = $GLOBALS['dirpre'];
      $GLOBALS['dirpre'] = $GLOBALS['dirpreFromRoute'];
      global $viewVars;
      require_once("$dirpreOrig$relPath");
      $GLOBALS['dirpre'] = $dirpreOrig;
    }
  }

  global $params;
  $params = $_POST;

  // REFACTOR ALL LOGIN/SESSION HANDLING CODE
  session_start();

  Controller::init();
?>
