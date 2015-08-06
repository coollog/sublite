<?php
  TEST('StudentModelTest.save', function() {
    $MStudentTest = new StudentModel(true);
    $id = $MStudentTest->save(array());
    TRUE($MStudentTest->exists($id));
  });
?>