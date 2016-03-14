<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface JobControllerAJAXInterface {
    /**
     * @param (skip, count)
     */
    public static function recent();
  }

  class JobControllerAJAX extends JobController
                              implements JobControllerAJAXInterface {
    public static function recent() {
      global $params;
      $skip = $params['skip'];
      $count = $params['count'];

      $data = [];

      $res = JobModel::recent($skip, $count);
      $jobs = self::processToView($res);
      $more = $skip + $count < JobModel::getSize();

      echo toJSON([ 'jobs' => $data, 'more' => $more ]);
    }

    private static function processToView(array $data) {
      $jobs = [];
      foreach ($data as $job) {
        $job['_id'] = $job['_id']->{'$id'};
        $company = CompanyModel::getById($job['company']);
        $job['company'] = $company['name'];
        $job['desc'] = strmax($job['desc'], 300);
        $job['logophoto'] = $company['logophoto'];
        $jobs[] = $job;
      }
      return $jobs;
    }
  }
?>