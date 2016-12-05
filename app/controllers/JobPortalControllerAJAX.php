<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface JobPortalControllerAJAXInterface {
    public static function mostPopular();
    public static function recent();
    public static function byIndustry();
  }

  class JobPortalControllerAJAX extends JobController
                                implements JobPortalControllerAJAXInterface {
    public static function mostPopular() {
      global $params;
      global $MJob;
      $skip = $params['skip'];
      $count = $params['count'];

      // Build the query; Issue the query.
      $jobs = $MJob->getAll();
      $topJobsSince = self::topViewersSince(90, $jobs, $skip, $count);
      $multipleIds = array();
      foreach($topJobsSince as $jobId) {
        array_push($multipleIds, new MongoId($jobId));
      }

      $idQuery = array('$in' => $multipleIds);
      $query = array('_id' => $idQuery);
      $topJobs = $MJob->find($query);

      // Send back JSON.
      $topJobsArray = iterator_to_array($topJobs);
      $jobs = self::processDBToView($topJobsArray);
      echo toJSON(['jobs' => $jobs]);
    }

    public static function recent() {
      global $params;
      $skip = $params['skip'];
      $count = $params['count'];

      self::search([], $skip, $count);
    }

    public static function byIndustry() {

    }


    // Takes a job document and extracts just the information to send back.
    private static function normalizeJob(array $job) {
      $job['_id'] = $job['_id']->{'$id'};
      $company = CompanyModel::getById($job['company']);
      $job['company'] = $company['name'];
      $job['desc'] = strmax($job['desc'], 300);
      $job['logophoto'] = $company['logophoto'];
      return $job;
    }

    private static function processDBToView(array $data) {
      $jobs = [];
      foreach ($data as $job) {
        $jobs[] = self::normalizeJob($job);
      }
      return $jobs;
    }

    private static function topViewersSince($days, $stats, $skip, $num) {
      $time = time() - 86400*$days;

      $statslength = sizeof($stats);
      for($i = 0; $i < $statslength; $i++) {
        $thisId = (string) $stats[$i]["_id"];
        $viewers = isset($stats[$i]["viewers"]) ? $stats[$i]["viewers"] : array();
        $count = 0;
        for($j = 0; $j < sizeof($viewers); $j++) {
          if($viewers[$j][1]->sec > $time) {
            $count++;
          }
        }

        $jobs[$thisId] = $count;
      }

      // Preferably, we could save this somewhere so we don't have to go through this every time.
      arsort($jobs);
      $jobs = array_slice($jobs, $skip, $num);
      //return $jobs;
      return array_keys($jobs);
    }

    private static function search(array $query, $skip, $count) {
      $total = 0;
      $res = JobModel::search($query, $skip, $count, $total);

      $more = $skip + $count < $total;

      // Save search to db.
      if ($skip == 0) AppModel::recordSearch('jobs');

      $jobs = self::processDBToView($res);
      echo toJSON([ 'jobs' => $jobs, 'more' => $more, 'total' => $total ]);
    }
  }
?>