<?php
  global $MSocial;

  $hubs = $MSocial->getAll();

  foreach ($hubs as $hub) {
    $longitude = $hub['location']['longitude'];
    $latitude = $hub['location']['latitude'];

    $hub['geojson'] = array(
      'type' => 'Point',
      'coordinates' => array($longitude, $latitude)
    );

    $MSocial->save($hub);
  }

  $MSocial->collection()->createIndex(array('geojson' => '2dsphere'));
?>