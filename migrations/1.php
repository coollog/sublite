<?php
  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    $job['fulltime'] = false;
    $MJob->save($job, false);
  }
?>