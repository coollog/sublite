<?php
  $students = StudentModel::getAll();

  foreach ($students as $student) {
    if (isset($student['orig'])) unset($student['orig']);

    StudentModel::save($student);
  }
?>