<?php
  function invariant($truthy, $errorMessage='') {
    if (!$truthy) {
      throw new Exception($errorMessage);
    }
  }

  function mongo_ok($response) {
    invariant(isset($response['ok']));
  }
?>