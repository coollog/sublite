<?php
  abstract Class Test {
    /**
     * Implement tests in this function.
     */
    abstract protected static function run();
  }

  class StudentModelTest extends Test {
    public static function run() {
      $class = get_called_class();

      TEST("$class.save", function() {
        self::start();
        $id = self::$MStudentTest->save(array());
        TRUE(self::$MStudentTest->exists($id));
      });
    }

    private static $MStudentTest;

    private static function start() {
      Model::test = true;

      self::$MStudentTest = new StudentModel();
    }
  }
?>