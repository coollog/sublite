<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface JobControllerAJAXInterface {
    /**
     * @param (query, skip, count)
     */
    public static function search();
  }

  class JobControllerAJAX extends JobController
                          implements JobControllerAJAXInterface {
    public static function search() {
      global $params;
      $skip = $params['skip'];
      $count = $params['count'];

      if (isset($params['query'])) {
        $industry = $params['query']['industry'];
        $city = $params['query']['city'];
        $recruiterId = $params['query']['recruiterId'];
        $companyId = $params['query']['companyId'];
        $title = $params['query']['title'];

        // Search query building
        $query = [];

        if (strlen($title) > 0) $query['$text'] = ['$search' => $title];
        if (strlen($recruiterId) > 0)
          $query['recruiter'] = new MongoId($recruiterId);
        if (strlen($companyId) > 0) {
          $query['company'] = new MongoId($companyId);
        } else {
          $companies = self::findCompanyIdsByIndustry($industry, $city);
          if (count($companies) > 0) $query['company'] = ['$in' => $companies];
        }
        // Location match city.
        if (strlen($city) > 0) {
          // TODO: Add filter for changing $maxDistance.
          $geocode = Geocode::geocode($city);
          if (!is_null($geocode)) {
            $point = new GeoPoint($geocode['latitude'], $geocode['longitude']);
            $query['geoJSON'] = [
              '$near' => [
                '$geometry' => $point->toArray(),
                '$maxDistance' => miles2meters(10)
              ]
            ];
          }
        }
      } else {
        $query = [];
      }

      // Performing search
      $total = 0;
      $res = JobModel::search($query, $skip, $count, $total);
      $jobs = self::processDBToView($res);
      $more = $skip + $count < $total;

      // Save search to db.
      if ($skip == 0) AppModel::recordSearch('jobs');

      echo toJSON([ 'jobs' => $jobs, 'more' => $more ]);
    }

    private static function findCompanyIdsByIndustry($industry) {
      $companies = CompanyModel::getByIndustry($industry);
      return getValuesOfKey($companies, '_id');
    }

    private static function processDBToView(array $data) {
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