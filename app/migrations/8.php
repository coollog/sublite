<?php
  $students = StudentModel::getAll();

  foreach ($students as $student) {
    if (!isset($student['gender'])) $student['gender'] = '';
    if (isset($student['pic'])) {
      $student['photo'] = $student['pic'];
      unset($student['pic']);
    }

    StudentModel::save($student);
  }
?>