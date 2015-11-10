<?php
	class LeaderboardCreater {
		private $dbstudent, $db;

		function __construct() {
	      // Setup database
	      $m = new MongoClient($GLOBALS['dburistudent']);
	      $this->dbstudent = $m->$GLOBALS['dbnamestudent'];
	      $m = new MongoClient($GLOBALS['dburi']);
	      $this->db = $m->$GLOBALS['dbname'];
	    }

		public $start_epoch_time = 1445653816;
		public $day_time = 86400;

		/*  Gets an array of schools mapped to counts of number of sign-ups 
			since a given time, expressed as seconds since epoch. */
		function school_count_since_date($time)
		{
			$S = new Schools();

			//Best way to connect to database?
			$cursor = $this->dbstudent->emails->find();

			$pattern = "/(.*)@(.*)/";
			$schools = array();
			$school_names = array();

			foreach($cursor as $doc)
			{
				$success = preg_match($pattern, $doc["email"], $match);
				if($success && $doc["_id"]->getTimestamp() > $time)
				{
					if(array_key_exists($match[2], $schools))
						$schools[$match[2]] += 1;
					else
						$schools[$match[2]] = 1;
				}
			}

			foreach($schools as $key => $id)
			{
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

		function specific_school_count_since_date($school_array, $time)
		{
			$all_schools = $this->school_count_since_date($time);
			$specific_schools = array();

			foreach($school_array as $school)
			{
				if(array_key_exists($school, $all_schools))
					$specific_schools[$school] = $all_schools[$school];
				else
					$specific_schools[$school] = 0;
			}

			return $specific_schools;
			
		}

		function specific_school_count_past_number_days($school_array, $days)
		{
			return $this->specific_school_count_since_date($school_array, time()-($days * $this->day_time));
		}


		/*  Takes in a key => value and returns a splice of the top 
			$number by value. */
		function get_highest_schools($school_names, $number)
		{
			arsort($school_names);
			return array_slice($school_names, 0, $number);
		}

		function specific_school_per_day($my_school, $number_intervals)
		{
			$cursor = $this->dbstudent->emails->find();
			
			$pattern = "/(.*)@(.*)/";

			$time_now = time()-18000;
			$intervals = array();
			$result_array = array();

			for($x = $number_intervals; $x > 0; $x--)
			{
				array_push($intervals, (strtotime("today -" . ($x-1) . " day")-18000));
				array_push($result_array, array(date('D', (strtotime("today -" . ($x-1) . " day")-18000)), 0));
			}
			array_push($intervals, $time_now);

			//$intervals = array_map(function ($x) { return $x-46035245; } , $intervals);

			foreach($cursor as $doc)
			{
				$success = preg_match($pattern, $doc["email"], $match);
				if($success && $match[2] == $my_school)
				{
					$doc_time = $doc["_id"]->getTimeStamp();
					for($i = 0; $i < $number_intervals; $i++)
					{
						if($doc_time >= $intervals[$i] and $doc_time < $intervals[$i+1])
							$result_array[$i][1]++;
					}
				}
			}

			return $result_array;
		}

	}

	$CLeaderboardCreater = new LeaderboardCreater();
?>