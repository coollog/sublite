<?php
  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    if (!isset($job['startdate'])) $job['startdate'] = '';
    if (!isset($job['enddate'])) $job['enddate'] = '';
    if (!isset($job['jobtype'])) $job['jobtype'] = 'internship';
    if (!isset($job['locationtype'])) $job['locationtype'] = '';
    
    $MJob->save($job, false);
  }
?>