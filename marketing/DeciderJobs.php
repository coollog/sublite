<?php
  require_once('ProcessSearch.php');
  require_once('JobListings.php');
  require_once('CompanyListings.php');

  require_once('Decider.php');
  class DeciderJobs extends Decider {
    public static function decide(MongoCollection $collJobs,
                                  MongoCollection $collCompanies) {
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
        'searchesCities' => $searchCityStates,
        'jobsCities' => $jobCityStates,
        'searchesIndustries' => $searchIndustries,
        'companyIndustries' => $companyIndustries,
        'demandCities' => $demandCities,
        'demandIndustries' => $demandIndustries,
        'recommendationCities' => $recommendationCities,
        'recommendationIndustries' => $recommendationIndustries
      ];
    }
  }
?>