<?php
  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    $job['geocode'] = geocode($job['location']);

    JobModel::save($job, false);
  }
?>