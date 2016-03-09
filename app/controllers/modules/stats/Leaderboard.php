<?php
  interface LeaderboardInterface {
    public static function getSchoolCountSince(array $schools, $time);
    public static function getSchoolCountDaysBefore(array $schools, $days);

    /**
     * Takes in a key => value and returns a splice of the top 
     * $number by value.
     */
    public static function getHighestSchools(array $schoolNames, $number);
  }

  class Leaderboard implements LeaderboardInterface {
    const DAYTIME = 86400;

    public static function getSchoolCountSince(array $schools, $time) {
      $allSchools = self::schoolCountSince($time);
      $specificSchools = array();

      foreach($schools as $school) {
        if(array_key_exists($school, $allSchools))
          $specificSchools[$school] = $allSchools[$school];
        else
          $specificSchools[$school] = 0;
      }

      return $specificSchools;
      
    }

    public static function getSchoolCountSinceTimes(array $schools, array $times) {
      global $S;

      $cursor = StudentModel::getAll();

      $pattern = "/(.*)@(.*)/";
      $results = array();
      $schoolNames = array();
      foreach($times as $time) {
        $results[$time] = array();
        $schoolNames[$time] = array();
      }

      foreach($cursor as $doc) {
        $success = isset($doc["email"]) ? preg_match($pattern, $doc["email"], $match) : null;
        if($success) {
          foreach($times as $time) {
            if($doc["_id"]->getTimestamp() > $time) {
              if(array_key_exists($match[2], $results[$time]))
                $results[$time][$match[2]] += 1;
              else
                $results[$time][$match[2]] = 1;
            }
          }
        }
      }

      foreach($times as $time) {
        foreach($results[$time] as $key => $id) {
          if($S->hasSchoolOf($key))
            if(array_key_exists($S->nameOf($key), $schoolNames[$time]))
              $schoolNames[$time][$S->nameOf($key)] += $id;
            else
              $schoolNames[$time][$S->nameOf($key)] = $id;
          else
            if(array_key_exists($key, $schoolNames))
              $schoolNames[$time][$key] += $id;
            else
              $schoolNames[$time][$key] = $id;
        }
      }

      $specificSchools = array();

      foreach($times as $time) {
        foreach($schools as $school) {
          if(array_key_exists($school, $schoolNames[$time]))
            $specificSchools[$time][$school] = $schoolNames[$time][$school];
          else
            $specificSchools[$time][$school] = 0;
        }
      }

      return $specificSchools;

    }

    public static function getSchoolCountDaysBefore(array $schools, $days) {
      return self::getSchoolCountSince($schools, time()-($days * self::DAYTIME));
    }

    public static function getHighestSchools(array $schoolNames, $number) {
      arsort($schoolNames);
      return array_slice($schoolNames, 0, $number);
    }

    /**
     * Gets an array of schools mapped to counts of number of sign-ups 
     * since a given time, expressed as seconds since epoch.
     */
    private static function schoolCountSince($time) {
      global $S;

      $cursor = StudentModel::getAll();

      $pattern = "/(.*)@(.*)/";
      $schools = array();
      $schoolNames = array();

      foreach($cursor as $doc) {
        $success = isset($doc["email"]) ? preg_match($pattern, $doc["email"], $match) : null;
        if($success && $doc["_id"]->getTimestamp() > $time) {
          if(array_key_exists($match[2], $schools))
            $schools[$match[2]] += 1;
          else
            $schools[$match[2]] = 1;
        }
      }

      foreach($schools as $key => $id) {
        if($S->hasSchoolOf($key))
          if(array_key_exists($S->nameOf($key), $schoolNames))
            $schoolNames[$S->nameOf($key)] += $id;
          else
            $schoolNames[$S->nameOf($key)] = $id;
        else
          if(array_key_exists($key, $schoolNames))
            $schoolNames[$key] += $id;
          else
            $schoolNames[$key] = $id;
      }

      return $schoolNames;
    }

    private static function getPerDayForSchool($schoolName, $numIntervals) {
      $cursor = StudentModel::getAll();
      
      $pattern = "/(.*)@(.*)/";

      $timeNow = time()-18000;
      $intervals = array();
      $resultArray = array();

      for($x = $numIntervals; $x > 0; $x--) {
        array_push($intervals, (strtotime("today -" . ($x-1) . " day")-18000));
        array_push($resultArray, array(date('D', (strtotime("today -" . ($x-1) . " day")-18000)), 0));
      }
      array_push($intervals, $timeNow);

      //$intervals = array_map(function ($x) { return $x-46035245; } , $intervals);

      foreach($cursor as $doc) {
        $success = preg_match($pattern, $doc["email"], $match);
        if($success && $match[2] == $schoolName) {
          $docTime = $doc["_id"]->getTimeStamp();
          for($i = 0; $i < $numIntervals; $i++) {
            if($docTime >= $intervals[$i] and $docTime < $intervals[$i+1])
              $resultArray[$i][1]++;
          }
        }
      }

      return $resultArray;
    }
  }
?>