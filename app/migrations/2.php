<?php
  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (!isset($job['startdate'])) $job['startdate'] = '';
    if (!isset($job['enddate'])) $job['enddate'] = '';
    if (!isset($job['jobtype'])) $job['jobtype'] = 'internship';
    if (!isset($job['locationtype'])) $job['locationtype'] = '';

    JobModel::save($job, false);
  }
?>