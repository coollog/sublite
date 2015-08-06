<?php
  function replaceTag($text, $tag, $left, $right) {
    while (($start = strpos($text, $tag)) !== false) {
      $subtext = substr($text, $start + strlen($tag));
      if (($end = strpos($subtext, $tag)) !== false) {
        $subtext = substr($subtext, 0, $end);
      }
      $replace = "$left$subtext$right";
      $text = substr_replace(
        $text, $replace, $start, strlen($subtext) + strlen($tag)*2);
    }

    return $text;
  }

  function CONSOLE($text, $newline='<p>') {
    $text = replaceTag(
      $text, '{red}', "<span style=\"color: red;\">", "</span>");
    $text = replaceTag(
      $text, '{green}', "<span style=\"color: green;\">", "</span>");
    $text = replaceTag($text, '*', "<b>", "</b>");

    echo "$newline$text";
  }


  function TEST($testName, $testFunc) {
    CONSOLE("Running test *$testName*...");

    try {
      $testFunc();
    } catch (Exception $e) {
      $eMsg = $e->getMessage();
      CONSOLE("Test *$testName* {red}FAILED{red}: $eMsg", "<br>");
      return;
    }

    CONSOLE("Test *$testName* {green}PASSED{green}!", "<br>");
  }

  function TRUE($truthy, $errorMessage='') {
    invariant($truthy, $errorMessage);
  }
?>

<style>
  html, body {
    height: 100%;
    margin: 0;
    box-sizing: border-box;
    padding: 0;
    font-family: monospace;
  }
  body {
    padding: 1em;
  }
</style>