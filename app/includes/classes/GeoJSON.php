<?php
  // Use GeoPoint for point GeoJSON.

  interface GeoJSONInterface {
    public static function fromArray(array $arr);
    public function toArray();
    public function getCoordinates();
  }

  class GeoJSON implements GeoJSONInterface {
    const POINT = 'Point';
    const MULTIPOINT = 'MultiPoint';

    public static function fromArray(array $arr) {
      return new GeoJSON($arr['type'], $arr['coordinates']);
    }

    public function __construct($type, $coordinates) {
      $this->type = $type;
      $this->coordinates = $coordinates;
    }

    public function toArray() {
      return [
        'type' => $this->type,
        'coordinates' => $this->coordinates
      ];
    }

    public function getCoordinates() {
      return $this->coordinates;
    }

    private $type;
    protected $coordinates;
  }

  class GeoPoint extends GeoJSON {
    public function __construct($latitude, $longitude) {
      parent::__construct(parent::POINT, [ $longitude, $latitude ]);
    }
  }

  class GeoMultiPoint extends GeoJSON {
    public function __construct($coordinates = []) {
      parent::__construct(parent::MULTIPOINT, $coordinates);
    }

    public function append($latitude, $longitude) {
      $this->coordinates[] = [ $longitude, $latitude ];
    }
  }
?>