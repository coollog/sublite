<?php
  global $MStudent;

  $students = $MStudent->getAll();

  foreach ($students as $student) {
    if (!isset($student['stats'])) $student['stats'] = array('referrals' => 0);
    
    $MStudent->save($student);
  }
?>