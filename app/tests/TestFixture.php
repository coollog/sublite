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


  function TEST($self, $testName, $testFunc) {
    $self::start();

    CONSOLE("Running test *$testName*...");

    try {
      $testFunc($self);
    } catch (Exception $e) {
      $eMsg = $e->getMessage();
      CONSOLE("Test *$testName* {red}FAILED{red}: $eMsg", "<br>");
      $self::end();
      return;
    }

    CONSOLE("Test *$testName* {green}PASSED{green}!", "<br>");
    $self::end();
  }

  function TRUE($truthy, $errorMessage='') {
    invariant($truthy, $errorMessage);
  }
  function FALSE($truthy, $errorMessage='') {
    TRUE(!$truthy, $errorMessage);
  }
  function EQ($val1, $val2, $errorMessage='') {
    TRUE($val1 == $val2, $errorMessage);
  }
  function NEQ($val1, $val2, $errorMessage='') {
    TRUE($val1 != $val2, $errorMessage);
  }

  interface TestInterface {
    /**
     * Implement tests in this function.
     */
    public static function run();

    /**
     * Initialization code for all tests should go here.
     */
    public static function start();

    /**
     * Wrap-up code for all tests should go here.
     */
    public static function end();
  }

  class Test {
    /**
     * Makes a private method usable for testing.
     */
    protected static function callPrivateMethod($className,
                                                $methodName) {
      $method = new ReflectionMethod($className, $methodName);
      $method->setAccessible(true);

      $args = func_get_args();
      array_shift($args); array_shift($args);
      return $method->invokeArgs(null, $args);
    }
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