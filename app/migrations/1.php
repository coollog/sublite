<?php
  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (!isset($job['fulltime']))
      $job['fulltime'] = false;
    JobModel::save($job, false);
  }
?>