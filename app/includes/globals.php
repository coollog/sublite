<?php
  /**
   * Sets a variable in the global scope so that it can be aliased using
   * 'global'.
   */
  function GLOBALvarSet($varName, $val) {
    $GLOBALS['GLOBALS'][$varName] = $val;
  }

  /**
   * Gets the value of a variable in the global scope. Same as using 'global'.
   */
  function GLOBALvarGet($varName) {
    return $GLOBALS['GLOBALS'][$varName];
  }
?>