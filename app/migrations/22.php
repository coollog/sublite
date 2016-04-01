<?php
  // Update all jobs with GeoJSON data.

  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    unset($job['geojson']);

    $geocode = Geocode::geocode($job['location']);
    if (is_null($geocode)) {
      echo "Failed to update job geoJSON on: $job[location]<br>";
      continue;
    }

    $point = new GeoPoint($geocode['latitude'], $geocode['longitude']);
    $job['geoJSON'] = $point->toArray();

    JobModel::save($job, false);
  }
?>