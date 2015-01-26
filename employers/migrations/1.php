<?php
  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    if (!isset($job['fulltime']))
      $job['fulltime'] = false;
    $MJob->save($job, false);
  }
?>