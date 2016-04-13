<?php
  // Use GeoPoint for point GeoJSON.

  class GeoJSON {
    const POINT = 'Point';
    const MULTIPOINT = 'MultiPoint';

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
    protected $coordinates;
  }

  class GeoPoint extends GeoJSON {
    public function __construct($latitude, $longitude) {
      parent::__construct(parent::POINT, [ $longitude, $latitude ]);
    }
  }

  class GeoMultiPoint extends GeoJSON {
    public function __construct($coordinates) {
      if (!isset($coordinates)) $coordinates = [];
      parent::__construct(parent::MULTIPOINT, $coordinates);
    }

    public function append($latitude, $longitude) {
      $this->coordinates[] = [ $longitude, $latitude ];
    }
  }
?>