<?php
  $students = StudentModel::getAll();

  foreach ($students as $student) {
    if (!isset($student['stats'])) $student['stats'] = array('referrals' => 0);

    StudentModel::save($student);
  }
?>