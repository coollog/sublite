<?php
  // Add 'application' field to all jobs.

  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (!isset($job['application'])) $job['application'] = null;

    JobModel::save($job, false);
  }
?>