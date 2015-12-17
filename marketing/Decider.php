<?php
  class Decider {
    const DEMAND_BRACKET_RANGE = 50;
    const DEMAND_BRACKET_COUNT = 5;

    protected static function getDemand(array $searchCounts,
                                        array $listingCounts) {
      $demand = [];
      foreach ($searchCounts as $key => $searchCount) {
        $listingCount = 0;
        if (isset($listingCounts[$key])) {
          $listingCount = $listingCounts[$key];
        }
        $demand[$key] = $searchCount - $listingCount;
      }

      arsort($demand);
      return $demand;
    }

    protected static function getRecommendation(array $demand) {
      $priorities = [
        1 => [],
        2 => [],
        3 => []
      ];

      $curPriority = 1;
      foreach ($demand as $key => $count) {
        if (count($priorities[$curPriority]) >= self::DEMAND_BRACKET_COUNT) {
          $curPriority ++;
          if ($curPriority > 3) break;
        }

        $curListCount = count($priorities[$curPriority]);
        if ($curListCount > 0) {
          $lastKey = $priorities[$curPriority][$curListCount - 1];
          $lastCount = $demand[$lastKey];
          if ($lastCount - $count > self::DEMAND_BRACKET_RANGE) {
            $curPriority ++;
            if ($curPriority > 3) break;
          }
        }

        $priorities[$curPriority][] = $key;
      }

      return $priorities;
    }
  }
?>