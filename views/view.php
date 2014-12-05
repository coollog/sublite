<?php
  function vecho($var, $format = null) {
    global $viewVars;
    if (isset($viewVars[$var])) {
      $var = $viewVars[$var];
      if ($format == null) $format = "{var}";
      $format = str_replace("{var}", $var, $format);
      echo $format;
    }
  }
  function vget($var) {
    global $viewVars;
    return $viewVars[$var];
  }
  function vnotice() {
    vecho('Success', "<div class=\"success\">{var}</div>");
    vecho('Error', "<div class=\"error\">{var}</div>");
  }
?>