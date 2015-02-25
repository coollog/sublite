<?php
  global $MStudent;

  $students = $MStudent->getAll();

  foreach ($students as $student) {
    if (!isset($student['gender'])) $student['gender'] = '';
    
    $MStudent->save($student);
  }
?>