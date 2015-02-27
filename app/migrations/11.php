<?php
  global $MStudent;

  $students = $MStudent->getAll();

  foreach ($students as $student) {
    if (!isset($student['bio'])) $student['bio'] = '';
    
    $MStudent->save($student);
  }
?>