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
      $skip = $params['skip'];
      $count = $params['count'];

      // Build the query.

      // Issue the query.

      // Massage the results.

      // Send back JSON.
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