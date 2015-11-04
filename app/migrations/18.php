<?php
  global $MStudent;

  $students = $MStudent->getAll();

  foreach ($students as $student) {
    if (isset($student['orig'])) unset($student['orig']);

    $MStudent->save($student);
  }
?>