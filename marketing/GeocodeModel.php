<?php
  class GeocodeModel {
    public static function init(MongoCollection $coll) {
      self::$coll = $coll;
    }
    private static function lookup($location) {
      $location = strtolower($location);
      $entry =
        self::$coll->findOne(['location' => $location], ['geocode' => 1]);
      if (is_null($entry)) return null;
      return $entry['geocode'];
    }
    private static function record($location, array $geocode) {
      $location = strtolower($location);
      if (!is_null(self::lookup($location))) return;
      $data = [
        'location' => $location,
        'geocode' => $geocode
      ];
      self::$coll->insert($data);
    }
    public static function get($location) {
      $geocode = self::lookup($location);
      if (!is_null($geocode)) return $geocode;

      $geocode = geocode($location);
      if (is_null($geocode)) return null;

      self::record($location, $geocode);
      return $geocode;
    }
    private static $coll;
  }
?>