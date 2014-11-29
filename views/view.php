<?php
  function vecho($var) {
    global $viewVars;
    if (isset($viewVars[$var])) echo $viewVars[$var];
  }
?>