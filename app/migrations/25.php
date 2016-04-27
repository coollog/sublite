<?php
  // Update all jobs with GeoJSON data.

  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (is_array($job['location'])) continue;

    $job['location'] = [ $job['location'] ];

    if (isset($job['geoJSON'])) {
      // Convert geoJSON to MultiPoint.
      $point = GeoJSON::fromArray($job['geoJSON']);
      $multiPoint = new GeoMultiPoint([ $point->getCoordinates() ]);

      $job['geoJSON'] = $multiPoint->toArray();
    }

    JobModel::save($job, false);
  }
?>