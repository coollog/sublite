<?php
  $students = StudentModel::getAll();

  foreach ($students as $student) {
    if (!isset($student['bio'])) $student['bio'] = '';

    StudentModel::save($student);
  }
?>