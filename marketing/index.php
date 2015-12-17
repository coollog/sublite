<?php
  error_reporting(E_ALL & ~E_STRICT);
  ini_set('display_errors', '1');

  require_once('pass.php'); // Imports the MongoURI for the database.
  require_once('geocode.php');

  // Connect to the databases.
  $m1 = new MongoClient($dbUri1);
  $m2 = new MongoClient($dbUri2);
  $db1 = $m1->sublite;
  $db2 = $m2->subliteinternships;
  $collGeocodes = $db1->geocodes;
  $collGeocodes->createIndex(['location' => 1]);

  class GeocodeModel {
    public static function lookup($location) {
      $location = strtolower($location);
      $entry =
        $collGeocodes->findOne(['location' => $location], ['geocode' => 1]);
      if (is_null($entry)) return null;
      return $entry['geocode'];
    }
    public static function record($location, array $geocode) {
      $location = strtolower($location);
      if (!is_null(self::lookup($location))) return;
      $data = [
        'location' => $location,
        'geocode' => $geocode
      ];
      $collGeocodes->insert($data);
    }
    public static function get($location) {
      $geocode = geocode($location);
    }
  }

  // Get the search data.
  $searches0 = $db2->app->findOne(['_id' => 'searches']);
  $searches1 = $db2->app->findOne(['_id' => 'searches1']);
  $searches2 = $db2->app->findOne(['_id' => 'searches2']);

  $searches = array_merge($searches0, $searches1, $searches2);

  // Process sublet searches for locations.
  function processSubletLocations(array $searches) {
    $locationHash = [];

    // Add one count for each unique location per email.
    foreach ($searches as $time => $search) {
      if (!is_array($search)) continue;
      if ($search['type'] != 'sublets') continue;

      $email = $search['email'];
      $location = strtolower($search['data']['location']);
      if (isset($locationHash[$location])) {
        if (isset($locationHash[$location]['email'][$email])) continue;
        $locationHash[$location]['email'][$email] = true;
        $locationHash[$location]['count'] ++;
      } else {
        $locationHash[$location] = [
          'email' => [$email => true],
          'count' => 1
        ];
      }
    }

    // Extract counts.
    $locationCounts = [];
    foreach ($locationHash as $location => $data) {
      $locationCounts[$location] = $data['count'];
    }

    arsort($locationCounts);
    return $locationCounts;
  }
  var_dump(processSubletLocations($searches));
?>