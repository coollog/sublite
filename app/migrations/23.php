<?php
  // Update all housing listings with GeoJSON data.

  $listings = SubletModel::getAll();

  foreach ($listings as $listing) {
    $geocode = $listing['geocode'];
    if (is_null($geocode)) {
      echo "Failed to update job geoJSON on: $listing[_id]<br>";
      continue;
    }
    $point = new GeoPoint($geocode['latitude'], $geocode['longitude']);
    $listing['geoJSON'] = $point->toArray();

    SubletModel::save($listing, false);
  }
?>
