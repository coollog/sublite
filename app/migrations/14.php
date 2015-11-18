<?php
  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (!isset($job['viewers'])) $job['viewers'] = array();

    JobModel::save($job, false);
  }
?>