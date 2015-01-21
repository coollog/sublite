<?php
  global $MJob;

  $jobs = $MJob->getAll();

  foreach ($jobs as $job) {
    if (!isset($job['stats'])) $job['stats'] = array(
      'views' => 0, 'clicks' => 0
    );
    
    $MJob->save($job, false);
  }
?>