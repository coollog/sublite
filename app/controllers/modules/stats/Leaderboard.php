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
      $all_schools = self::schoolCountSince($time);
      $specific_schools = array();

      foreach($schools as $school) {
        if(array_key_exists($school, $all_schools))
          $specific_schools[$school] = $all_schools[$school];
        else
          $specific_schools[$school] = 0;
      }

      return $specific_schools;
      
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
      $school_names = array();

      foreach($cursor as $doc) {
        $success = preg_match($pattern, $doc["email"], $match);
        if($success && $doc["_id"]->getTimestamp() > $time) {
          if(array_key_exists($match[2], $schools))
            $schools[$match[2]] += 1;
          else
            $schools[$match[2]] = 1;
        }
      }

      foreach($schools as $key => $id) {
        if($S->hasSchoolOf($key))
          if(array_key_exists($S->nameOf($key), $school_names))
            $school_names[$S->nameOf($key)] += $id;
          else
            $school_names[$S->nameOf($key)] = $id;
        else
          if(array_key_exists($key, $school_names))
            $school_names[$key] += $id;
          else
            $school_names[$key] = $id;
      }

      return $school_names;
    }

    private static function getPerDayForSchool($schoolName, $numIntervals) {
      $cursor = StudentModel::getAll();
      
      $pattern = "/(.*)@(.*)/";

      $time_now = time()-18000;
      $intervals = array();
      $result_array = array();

      for($x = $numIntervals; $x > 0; $x--) {
        array_push($intervals, (strtotime("today -" . ($x-1) . " day")-18000));
        array_push($result_array, array(date('D', (strtotime("today -" . ($x-1) . " day")-18000)), 0));
      }
      array_push($intervals, $time_now);

      //$intervals = array_map(function ($x) { return $x-46035245; } , $intervals);

      foreach($cursor as $doc) {
        $success = preg_match($pattern, $doc["email"], $match);
        if($success && $match[2] == $schoolName) {
          $doc_time = $doc["_id"]->getTimeStamp();
          for($i = 0; $i < $numIntervals; $i++) {
            if($doc_time >= $intervals[$i] and $doc_time < $intervals[$i+1])
              $result_array[$i][1]++;
          }
        }
      }

      return $result_array;
    }
  }
?>