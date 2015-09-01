<?php
  abstract Class Test {
    /**
     * Implement tests in this function.
     */
    abstract protected static function run();
  }

  class StudentModelTest extends Test {
// PUBLIC:
    public static function run() {
      $class = get_called_class();

      TEST("$class.save", function() {
        self::start();
        $id = self::$MStudentTest->save(array());
        TRUE(self::$MStudentTest->exists($id));
      });
    }

// PRIVATE:
    private static $MStudentTest;

    private static function start() {
      self::$MStudentTest = new StudentModel(true);
    }
  }
?>