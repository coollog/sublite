<?php
  require_once('GeocodeModel.php');

  class CompanyListings {
    public static function init(MongoCollection $coll) {
      self::$coll = $coll;
    }

    public static function processIndustries() {
      $companies = self::$coll->find();

      $industryCounts = [];

      foreach ($companies as $company) {
        if (empty($company['industry'])) continue;

        $industries = explode(', ', $company['industry']);

        foreach ($industries as $industry) {
          $industry = strtolower($industry);

          if (isset($industryCounts[$industry])) {
            $industryCounts[$industry] ++;
          } else {
            $industryCounts[$industry] = 1;
          }
        }
      }

      arsort($industryCounts);
      return $industryCounts;
    }

    private static $coll;
  }
?>