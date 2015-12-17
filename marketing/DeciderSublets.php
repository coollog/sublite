<?php
  require_once('ProcessSearch.php');
  require_once('SubletListings.php');

  require_once('Decider.php');
  class DeciderSublets extends Decider {
    public static function decide(MongoCollection $coll, array $searches) {
      ProcessSearch::init($searches);
      $searchCityStates = ProcessSearch::processSubletLocations();

      SubletListings::init($coll);
      $subletCityStates = SubletListings::processLocations();

      // Get demand of each searched city.
      $demand = self::getDemand($searchCityStates, $subletCityStates);

      $recommendation = self::getRecommendation($demand);

      return [
        'searches' => $searchCityStates,
        'sublets' => $subletCityStates,
        'demand' => $demand,
        'recommendation' => $recommendation
      ];
    }
  }
?>