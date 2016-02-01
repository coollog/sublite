<?php
  require_once('GeocodeModel.php');
  require_once('StateAbbr.php');

  class SubletListings {
    public static function init(MongoCollection $coll) {
      self::$coll = $coll;
    }

    public static function processLocations() {
      $sublets = self::$coll->find();

      $cityStateCounts = [];

      foreach ($sublets as $sublet) {
        if (empty($sublet['address']) ||
            empty($sublet['city']) ||
            empty($sublet['state'])) continue;

        $address = $sublet['address'];
        $city = $sublet['city'];
        $state = $sublet['state'];

        $location = "$address, $city, $state";
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