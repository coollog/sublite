<?php
  require_once('GeocodeModel.php');
  require_once('StateAbbr.php');

  class JobListings {
    public static function init(MongoCollection $coll) {
      self::$coll = $coll;
    }

    public static function processLocations() {
      $jobs = self::$coll->find();

      $cityStateCounts = [];

      foreach ($jobs as $job) {
        if (empty($job['location'])) continue;

        $location = $job['location'];

        $geocode = GeocodeModel::get($location);
        if (is_null($geocode)) continue;
        $cityState = strtolower(getCityStateFromGeocode($geocode));

        if (isset($cityStateCounts[$cityState])) {
          $cityStateCounts[$cityState] ++;
        } else {
          $cityStateCounts[$cityState] = 1;
        }
      }

      arsort($cityStateCounts);
      return $cityStateCounts;
    }

    private static $coll;
  }
?>