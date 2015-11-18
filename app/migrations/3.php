<?php
  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (!isset($job['stats'])) $job['stats'] = array(
      'views' => 0, 'clicks' => 0
    );

    JobModel::save($job, false);
  }
?>