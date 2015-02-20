<?php
  // Sets up new subletting model

  // MIGRATIONS NEEDED:
  // - change location to address
  // - change N and W to just geocode object with also "location_type": "APPROXIMATE"
  // - change from to startdate and to to enddate
  // - change occ to occupancy
  // - change building to buildingtype
  // - change room to roomtype
  // - add pricetype
  // - add gender
  // - change imgs to photos

  global $MSublet;

  $sublets = $MSublet->getAll();

  foreach ($sublets as $sublet) {
    if (isset($sublet['location'])) {
      $sublet['address'] = $sublet['location'];
      unset($sublet['location']);
    }
    if (isset($sublet['N']) and isset($sublet['W'])) {
      $sublet['geocode'] = array(
        'latitude' => $sublet['N'],
        'longitude' => $sublet['W'],
        'location_type' => 'APPROXIMATE'
      );
      unset($sublet['N']);
      unset($sublet['W']);
    }
    if (isset($sublet['from'])) {
      $sublet['startdate'] = $sublet['from'];
      unset($sublet['from']);
    }
    if (isset($sublet['to'])) {
      $sublet['enddate'] = $sublet['to'];
      unset($sublet['to']);
    }
    if (isset($sublet['occ'])) {
      $sublet['occupancy'] = $sublet['occ'];
      unset($sublet['occ']);
    }
    if (isset($sublet['building'])) {
      $sublet['buildingtype'] = $sublet['building'];
      unset($sublet['building']);
    }
    if (isset($sublet['room'])) {
      $sublet['roomtype'] = $sublet['room'];
      unset($sublet['room']);
    }
    if (!isset($sublet['pricetype']))
      $sublet['pricetype'] = 'week';
    if (!isset($sublet['gender']))
      $sublet['gender'] = '';
    if (isset($sublet['imgs'])) {
      $sublet['photos'] = $sublet['imgs'];
      unset($sublet['imgs']);
    }
    
    $MSublet->save($sublet, false);
  }
?>