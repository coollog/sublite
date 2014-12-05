<?php
  function vecho($var) {
    global $viewVars;
    if (isset($viewVars[$var])) echo $viewVars[$var];
  }
  function vget($var) {
    global $viewVars;
    return $viewVars[$var];
  }
?>