<?php
  function vprocess() {
    global $viewVars;
    if (isset($_SESSION['loggedin'])) {
      $viewVars = array_merge($viewVars, array(
        'Loggedin' => true,
        'L_id' => $_SESSION['_id'],
        'Lemail' => $_SESSION['email'],
        'Lpass' => $_SESSION['pass'],
        'Lcompany' => isset($_SESSION['company'])
      ));
    } else {
      $viewVars['Loggedin'] = false;
    }
    if (isset($_SESSION['loggedinstudent'])) {
      if (isset($_SESSION['name'])) $name = $_SESSION['name'];
      else $name = '';
      if (isset($_SESSION['_id'])) {
        Controller::redirect($GLOBALS['dirpre'] . '../logout');
        return;
      }

      $viewVars = array_merge($viewVars, array(
        'Loggedinstudent' => true,
        'L_id' => $_SESSION['_id'],
        'Lemail' => $_SESSION['email'],
        'Lpass' => $_SESSION['pass'],
        'Lname' => $name
      ));
    } else {
      $viewVars['Loggedinstudent'] = false;
    }
  }
  function vecho($var, $format=null, $default='') {
    global $viewVars;
    vprocess();
    if (isset($viewVars[$var]) and strlen($viewVars[$var]) > 0) {
      $var = $viewVars[$var];
      if ($format == null) $format = "{var}";
      $format = str_replace("{var}", $var, $format);
      echo html_entity_decode($format);
    } else
      echo $default;
  }
  function vget($var) {
    global $viewVars;
    vprocess();
    if (isset($viewVars[$var])) return $viewVars[$var];
    else return null;
  }
  function vnotice() {
    vecho('Success', "<div class=\"success\">{var}</div>");
    vecho('Error', "<div class=\"error\">{var}</div>");
  }
  function vchecked($var, $val) {
    global $viewVars;
    if (isset($viewVars[$var])) {
      if ((is_array($viewVars[$var]) and in_array($val, $viewVars[$var])) or
          $viewVars[$var] == $val) {
        echo 'checked';
      }
    }
  }
  function vlinkto($in, $page, $params = null, $newtab = false) {
    if ($newtab) $newtab = 'target="_blank"';
    else $newtab = '';

    if ($params == NULL) $query = '';
    else $query = '?' . http_build_query($params);

    return "<a href=\"$page.php$query\" $newtab>$in</a>";
  }
  function vpartial($page, $vars = false) {
    global $viewVars;
    if ($vars) $viewVars = array_merge($viewVars, $vars);

    require($GLOBALS['dirpre']."views/partials/$page.php");
  }

  function jsecho($str) {
    echo str_replace(array("\r", "\n"), '', $str);
  }
?>
