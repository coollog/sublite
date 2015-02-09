<?php
  global $MStudent;

  $students = $MStudent->getAll();

  foreach ($students as $student) {
    if (isset($student['stats']['referrals'])) {
      if ($student['stats']['referrals'] == 0) {
        $student['stats']['referrals'] = array();
      }
    }
    
    $MStudent->save($student);
  }
?>