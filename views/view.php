<?php
  function vprocess() {
    global $viewVars;
    if (isset($_SESSION['loggedin'])) {
      $viewVars = array_merge($viewVars, array(
        'Loggedin' => true,
        'L_id' => $_SESSION['_id'],
        'Lemail' => $_SESSION['email'],
        'Lpass' => $_SESSION['pass']
      ));
    } else {
      $viewVars['Loggedin'] = false;
    }
  }
  function vecho($var, $format = null) {
    global $viewVars;
    vprocess();
    if (isset($viewVars[$var]) and strlen($viewVars[$var]) > 0) {
      $var = $viewVars[$var];
      if ($format == null) $format = "{var}";
      $format = str_replace("{var}", $var, $format);
      echo $format;
    }
  }
  function vget($var) {
    global $viewVars;
    vprocess();
    return $viewVars[$var];
  }
  function vnotice() {
    vecho('Success', "<div class=\"success\">{var}</div>");
    vecho('Error', "<div class=\"error\">{var}</div>");
  }
  function vchecked($var, $val) {
    global $viewVars;
    if (isset($viewVars[$var]) and $viewVars[$var] == $val) {
      echo 'checked';
    }
  }
  function vlinkto($in, $page, $params = null, $newtab = false) {
    if ($newtab) $newtab = 'target="_blank"';
    else $newtab = '';

    if ($params == NULL) $query = '';
    else $query = '?' . http_build_query($params);

    return "<a href=\"$page.php$query\" $newtab>$in</a>";
  }
?>