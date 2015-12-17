<?php
  interface GeocodeModelInterface {
    public function __construct();

    public static function lookup($location);
    public static function record($location, array $geocode);
  }

  class GeocodeModel extends Model implements GeocodeModelInterface {
    const DB_TYPE = parent::DB_STUDENTS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'geocodes');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(array('location' => 1)));
    }

    public static function lookup($location) {
      $location = strtolower($location);
      $query = (new DBQuery(self::$collection))
        ->toQuery('location', $location)->projectField('geocode');
      $entry = $query->findOne();
      if (is_null($entry)) {
        return null;
      }
      return $entry['geocode'];
    }

    public static function record($location, array $geocode) {
      $location = strtolower($location);
      if (!is_null(self::lookup($location))) {
        return;
      }

      $data = [
        'location' => $location,
        'geocode' => $geocode
      ];
      self::insert($data);
    }
  }

  new GeocodeModel();
?>