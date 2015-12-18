<?php
  require_once('GeocodeModel.php');

  class ProcessSearch {
    public static function init(array $searches) {
      self::$searches = $searches;
    }

    // Process sublet searches for locations.
    public static function processSubletLocations() {
      $cityStateHash = [];

      // Add one count for each unique location per email.
      foreach (self::$searches as $time => $search) {
        if (!is_array($search)) continue;
        if ($search['type'] != 'sublets') continue;

        $email = $search['email'];
        $location = strtolower($search['data']['location']);

        // Location to geocode, to City, State.
        $geocode = GeocodeModel::get($location);
        if (is_null($geocode)) continue;
        $cityState = strtolower(getCityStateFromGeocode($geocode));

        if (isset($cityStateHash[$cityState])) {
          if (isset($cityStateHash[$cityState]['email'][$email])) continue;
          $cityStateHash[$cityState]['email'][$email] = true;
          $cityStateHash[$cityState]['count'] ++;
        } else {
          $cityStateHash[$cityState] = [
            'email' => [$email => true],
            'count' => 1
          ];
        }
      }

      // Extract counts.
      $cityStateCounts = [];
      foreach ($cityStateHash as $cityState => $data) {
        $cityStateCounts[$cityState] = $data['count'];
      }

      arsort($cityStateCounts);
      return $cityStateCounts;
    }

    // Process job searches for locations.
    public static function processJobLocations() {
      $cityStateHash = [];

      // Add one count for each unique location per email.
      foreach (self::$searches as $time => $search) {
        if (!is_array($search)) continue;
        if ($search['type'] != 'jobs') continue;
        if (empty($search['data']['city'])) continue;

        $email = $search['email'];
        $location = strtolower($search['data']['city']);

        // Location to geocode, to City, State.
        $geocode = GeocodeModel::get($location);
        if (is_null($geocode)) continue;
        $cityState = strtolower(getCityStateFromGeocode($geocode));

        if (isset($cityStateHash[$cityState])) {
          if (isset($cityStateHash[$cityState]['email'][$email])) continue;
          $cityStateHash[$cityState]['email'][$email] = true;
          $cityStateHash[$cityState]['count'] ++;
        } else {
          $cityStateHash[$cityState] = [
            'email' => [$email => true],
            'count' => 1
          ];
        }
      }

      // Extract counts.
      $cityStateCounts = [];
      foreach ($cityStateHash as $cityState => $data) {
        $cityStateCounts[$cityState] = $data['count'];
      }

      arsort($cityStateCounts);
      return $cityStateCounts;
    }

    // Process job searches for industries.
    public static function processJobIndustries() {
      $industryHash = [];

      // Add one count for each unique industry per email.
      foreach (self::$searches as $time => $search) {
        if (!is_array($search)) continue;
        if ($search['type'] != 'jobs') continue;
        if (empty($search['data']['industry'])) continue;

        $email = $search['email'];
        $industry = strtolower($search['data']['industry']);

        if (isset($industryHash[$industry])) {
          if (isset($industryHash[$industry]['email'][$email])) continue;
          $industryHash[$industry]['email'][$email] = true;
          $industryHash[$industry]['count'] ++;
        } else {
          $industryHash[$industry] = [
            'email' => [$email => true],
            'count' => 1
          ];
        }
      }

      // Extract counts.
      $industryCounts = [];
      foreach ($industryHash as $industry => $data) {
        $industryCounts[$industry] = $data['count'];
      }

      arsort($industryCounts);
      return $industryCounts;
    }

    // Process sublets and jobs searches by student.
    public static function processByStudent($email) {
      $subletLocations = [];
      $jobLocations = [];
      $jobIndustries = [];

      foreach (self::$searches as $time => $search) {
        if (!is_array($search)) continue;
        if ($search['email'] != $email) continue;

        switch ($search['type']) {
          case 'sublets':
            if (!empty($search['data']['location'])) {
              $location = strtolower($search['data']['location']);

              // Location to geocode, to City, State.
              $geocode = GeocodeModel::get($location);
              if (is_null($geocode)) continue;
              $cityState = strtolower(getCityStateFromGeocode($geocode));

              if (isset($subletLocations[$cityState])) {
                $subletLocations[$cityState] ++;
              } else {
                $subletLocations[$cityState] = 1;
              }
            }
            break;
          case 'jobs':
            if (!empty($search['data']['city'])) {
              $location = strtolower($search['data']['city']);

              // Location to geocode, to City, State.
              $geocode = GeocodeModel::get($location);
              if (is_null($geocode)) continue;
              $cityState = strtolower(getCityStateFromGeocode($geocode));

              if (isset($jobLocations[$cityState])) {
                $jobLocations[$cityState] ++;
              } else {
                $jobLocations[$cityState] = 1;
              }
            }
            if (!empty($search['data']['industry'])) {
              $industry = strtolower($search['data']['industry']);
              if (isset($jobIndustries[$industry])) {
                $jobIndustries[$industry] ++;
              } else {
                $jobIndustries[$industry] = 1;
              }
            }
            break;
        }
      }

      arsort($subletLocations);
      arsort($jobLocations);
      arsort($jobIndustries);
      return [
        'subletLocations' => $subletLocations,
        'jobLocations' => $jobLocations,
        'jobIndustries' => $jobIndustries
      ];
    }

    public static function getEmails() {
      $emailsHash = [];

      foreach (self::$searches as $time => $search) {
        if (!is_array($search)) continue;

        $email = $search['email'];
        if (isset($emailsHash[$email])) {
          $emailsHash[$email] ++;
        } else {
          $emailsHash[$email] = 1;
        }
      }

      arsort($emailsHash);
      return $emailsHash;
    }

    private static $searches;
  }
?>