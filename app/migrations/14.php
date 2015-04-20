<?php
  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    if (!isset($job['viewers'])) $job['viewers'] = array();
    
    $MJob->save($job, false);
  }
?>