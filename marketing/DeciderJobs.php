<?php
  require_once('ProcessSearch.php');
  require_once('JobListings.php');
  require_once('CompanyListings.php');

  require_once('Decider.php');
  class DeciderJobs extends Decider {
    const DEMAND_BRACKET_RANGE = 50;
    const DEMAND_BRACKET_COUNT = 5;
    public static function decide(MongoCollection $collJobs,
                                  MongoCollection $collCompanies,
                                  array $searches) {
      ProcessSearch::init($searches);
      $searchCityStates = ProcessSearch::processJobLocations();
      $searchIndustries = ProcessSearch::processJobIndustries();

      JobListings::init($collJobs);
      $jobCityStates = JobListings::processLocations();

      CompanyListings::init($collCompanies);
      $companyIndustries = CompanyListings::processIndustries();

      // Get demand of each searched city.
      $demandCities = self::getDemand($searchCityStates, $jobCityStates);
      $demandIndustries = self::getDemand($searchIndustries, $companyIndustries);

      $recommendationCities = self::getRecommendation($demandCities);
      $recommendationIndustries = self::getRecommendation($demandIndustries);

      return [
        'searches' => $searchCityStates,
        'jobs' => $jobCityStates,
        'demandCities' => $demandCities,
        'demandIndustries' => $demandIndustries,
        'recommendationCities' => $recommendationCities,
        'recommendationIndustries' => $recommendationIndustries
      ];
    }
  }
?>