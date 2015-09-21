<?php
  // Add 'application' field to all jobs.

  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    if (!isset($job['application'])) $job['application'] = null;

    $MJob->save($job, false);
  }
?>