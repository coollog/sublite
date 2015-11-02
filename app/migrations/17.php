<?php
  // Add initial credits to each recruiter.

  $recruiters = RecruiterModel::getAll();

  foreach ($recruiters as $recruiter) {
    if (!isset($recruiter['credits'])) {
      $recruiterId = $recruiter['_id'];
      RecruiterModel::setCredits($recruiterId, 5);
    }
  }
?>