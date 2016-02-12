<?php
  require_once($dirpre.'includes/functions/geocode.php');

  interface GeocodeInterface {
    public static function geocode($location);
    public static function getCity($location);

    public function __construct(array $geocodeJSON);
    public function getLatitude();
    public function getLongitude();
    public function getLocationType();
    public function getAddressComponents();
  }

  class Geocode implements GeocodeInterface {
    public static function geocode($location) {
      $geocode = GeocodeModel::lookup($location);
      if (!is_null($geocode)) {
        $geocode = new Geocode($geocode);
        return $geocode->getLatitudeLongitudeType();
      }

      // Geocode was not in database, so perform Geocoding API request.
      $geocode = self::getGeocodeJSON($location);
      if (!is_null($geocode)) {
        $geocode = new Geocode($geocode);
        return $geocode->getLatitudeLongitudeType();
      }

      return null;
    }

    public static function getCity($location) {
      $geocode = GeocodeModel::lookup($location);
      if (is_null($geocode)) {
        // Geocode was not in database, so perform Geocoding API request.
        $geocode = self::getGeocodeJSON($location);
        if (is_null($geocode)) {
          return null;
        }
      }
      $geocode = new Geocode($geocode);

      $address_components = $geocode->getAddressComponents();

      $city = null;
      $state = null;
      foreach ($address_components as $c) {
        if (in_array("locality", $c['types'])) {
          $city = $c['short_name'];
        }
        if (in_array("administrative_area_level_1", $c['types'])) {
          $state = $c['short_name'];
        }
      }

      if (is_null($city) || is_null($state)) {
        return null;
      }

      return "$city, $state";
    }

    private static function getGeocodeJSON($location) {
      $geocode = geocodeJSON($location);
      if (is_null($geocode)) return null;

      // Store the geocode for the location.
      GeocodeModel::record($location, $geocode);

      return $geocode;
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(array $geocodeJSON) {
      invariant(!is_null($geocodeJSON));
      $this->data = $geocodeJSON;
    }

    public function getLatitudeLongitudeType() {
      return [
        'latitude' => $this->getLatitude(),
        'longitude' => $this->getLongitude(),
        'location_type' => $this->getLocationType(),
      ];
    }

    public function getLatitude() {
      $geometry = $this->getGeometry();
      if (is_null($geometry)) return $this->data['latitude'];
      $latitude = $geometry['location']['lat'];
      return $latitude;
    }

    public function getLongitude() {
      $geometry = $this->getGeometry();
      if (is_null($geometry)) return $this->data['longitude'];
      $longitude = $geometry['location']['lng'];
      return $longitude;
    }

    public function getLocationType() {
      $geometry = $this->getGeometry();
      if (is_null($geometry)) return $this->data['location_type'];
      $locationType = $geometry['location_type'];
      return $locationType;
    }

    public function getAddressComponents() {
      $firstResult = $this->getFirstResult();
      if (is_null($firstResult)) return null;
      return $firstResult['address_components'];
    }

    private function getGeometry() {
      $firstResult = $this->getFirstResult();
      if (is_null($firstResult)) return null;
      return $firstResult['geometry'];
    }

    private function getFirstResult() {
      if (!isset($this->data['results'])) return null;
      return $this->data['results'][0];
    }

    private $data;
  }
?>