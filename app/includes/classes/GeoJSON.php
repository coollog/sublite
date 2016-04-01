<?php
  // Use GeoPoint for point GeoJSON.

  class GeoJSON {
    const POINT = 'Point';

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

    private $type;
    private $coordinates;
  }

  class GeoPoint extends GeoJSON {
    public function __construct($latitude, $longitude) {
      parent::__construct(parent::POINT, [ $longitude, $latitude ]);
    }
  }
?>