<?php
  require_once($GLOBALS['dirpre'].'tests/TestFixture.php');

  class StudentModelTest extends Test implements TestInterface {
    public static function run() {
      $class = get_called_class();

      TEST($class, "$class.correctCollection", function() {
        $actualCollection = StudentModel::myCollection();
        $correctCollection = 'emails';
        EQ($actualCollection, $correctCollection,
           "Collection name invalid: $actualCollection");
      });

      TEST($class, "$class.save", function() {
        $id = self::$MStudentTest->save(array());
        TRUE(self::$MStudentTest->exists($id));
      });
    }

    public static function start() {
      self::$MStudentTest = new StudentModel();
    }

    public static function end() {
      self::$MStudentTest = null;
    }

    private static $MStudentTest;
  }
?>