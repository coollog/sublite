<?php
  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    $job['geocode'] = geocode($job['location']);
    
    $MJob->save($job, false);
  }
?>