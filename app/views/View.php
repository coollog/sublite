<?php
  interface ViewInterface {
    public static function echof($var, $format = null, $default = '');
    public static function echoCount($varName);
    public static function echoLink($link);
    public static function get($varName);
    public static function linkTo($html,
                                  $route,
                                  $params = null,
                                  $newTab = false);
    public static function partial($page, $vars = false);
    public static function vars();
    public static function notice();
    public static function checked($var, $val);
    public static function jsecho($str);
  }

  // Implement this and have the functions below as class methods.
  class View implements ViewInterface {
    public static function echoCount($varName) {
      echo count(View::get($varName));
    }
    public static function get($varName) {
      global $viewVars;
      View::process();
      if (isset($viewVars[$varName])) return $viewVars[$varName];
      else return null;
    }
    public static function echof($var, $format = null, $default = '') {
      global $viewVars;
      View::process();
      if (isset($viewVars[$var]) and strlen($viewVars[$var]) > 0) {
        $var = $viewVars[$var];
        if ($format == null) $format = "{var}";
        $format = str_replace("{var}", $var, $format);
        echo html_entity_decode($format);
      } else {
        echo $default;
      }
    }
    public static function echoArray($varName, $key) {
      $var = View::get($varName);
      echo $var[$key];
    }
    public static function echoLink($link) {
      echo "$GLOBALS[dirpre]../$link";
    }
    public static function linkTo($html,
                                  $route,
                                  $params = null,
                                  $newTab = false) {
      if ($newTab) $newTab = 'target="_blank"';
      else $newTab = '';

      if ($params == NULL) $query = '';
      else $query = '?' . http_build_query($params);

      return "<a href=\"$route$query\" $newTab>$html</a>";
    }
    public static function partial($page, $vars = false) {
      global $viewVars;
      if ($vars) $viewVars = array_merge($viewVars, $vars);

      require($GLOBALS['dirpreOrig']."views/partials/$page.php");
    }
    public static function vars() {
      global $viewVars;
      return $viewVars;
    }
    public static function notice() {
      $bugLink = '<br/>
        <small>
          <a href="'.$GLOBALS['dirpre'].'../feedback">Found a bug?</a>
        </small>';
      View::echof('Success', "<div class=\"success\">{var}</div>");
      View::echof('Error', "<div class=\"error\">{var}$bugLink</div>");
    }
  }

  function vprocess() {
    global $viewVars;
    if (isset($_SESSION['loggedin'])) {
      $viewVars = array_merge($viewVars, [
        'Loggedin' => true,
        'L_id' => $_SESSION['_id'],
        'Lemail' => $_SESSION['email'],
        'Lpass' => $_SESSION['pass'],
        'Lcompany' => isset($_SESSION['company'])
      ]);
    } else {
      $viewVars['Loggedin'] = false;
>>>>>>> finished student dashboard
    }
    public static function checked($var, $val) {
      global $viewVars;
      if (isset($viewVars[$var])) {
        if ((is_array($viewVars[$var]) and in_array($val, $viewVars[$var])) or
            $viewVars[$var] == $val) {
          echo 'checked';
        }
      }
    }
    public static function jsecho($str) {
      echo str_replace(array("\r", "\n"), '', $str);
    }

    private static function process() {
      global $viewVars;
      if (isset($_SESSION['loggedin'])) {
        $viewVars = array_merge($viewVars, [
          'Loggedin' => true,
          'L_id' => $_SESSION['_id'],
          'Lemail' => $_SESSION['email'],
          'Lpass' => $_SESSION['pass'],
          'Lcompany' => isset($_SESSION['company'])
        ]);
      } else {
        $viewVars['Loggedin'] = false;
      }
      if (isset($_SESSION['loggedinstudent'])) {
        if (isset($_SESSION['name'])) $name = $_SESSION['name'];
        else $name = '';
        if (!isset($_SESSION['_id'])) {
          Controller::redirect($GLOBALS['dirpre'] . '../logout');
          return;
        }

        $viewVars = array_merge($viewVars, [
          'Loggedinstudent' => true,
          'L_id' => $_SESSION['_id'],
          'Lemail' => $_SESSION['email'],
          'Lpass' => $_SESSION['pass'],
          'Lname' => $name
        ]);
      } else {
        $viewVars['Loggedinstudent'] = false;
      }
    }
  }

  $GLOBALS['dirpreOrig'] = $GLOBALS['dirpre'];
?>
