<?php
  require_once($dirpre.'tests/TestFixture.php');
  require_once($dirpre.'controllers/modules/application/Application.php');
  require_once($dirpre.'controllers/modules/application/ApplicationStudent.php');
  require_once($dirpre.'controllers/modules/application/ApplicationJob.php');

  class ApplicationTest extends Test implements TestInterface {
    public static function run() {
      $class = get_called_class();

      TEST($class, "$class.", function ($class) {

      });
    }

    public static function start() {
      self::$MApplicationTest = new ApplicationModel();
    }

    public static function end() {
      self::$MApplicationTest = null;
    }

    private static $MApplicationTest;
  }
?>