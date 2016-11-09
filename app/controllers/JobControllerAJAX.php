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

      $query = [];
      $queryNear = [];
      $total = 0;
      if (isset($params['query'])) {
        $query = self::buildSearchQuery($params['query']);

        $queryNear = self::buildSearchQueryNear($query, $params['query']);
        $resNear = JobModel::search($queryNear, 0, 0, $total);

        $query = self::buildSearchQueryText($query, $params['query'], $resNear);
      }
      $res = JobModel::search($query, $skip, $count, $total);

      $more = $skip + $count < $total;

      // Save search to db.
      if ($skip == 0) AppModel::recordSearch('jobs');

      $jobs = self::processDBToView($res);
      echo toJSON([ 'jobs' => $jobs, 'more' => $more, 'total' => $total ]);
    }

    // Builds search query without $near and $text.
    private static function buildSearchQuery(array $params) {
      $industry = $params['industry'];
      $recruiterId = $params['recruiterId'];
      $companyId = $params['companyId'];
      $jobtype = $params['jobtype'];
      $salarytype = $params['salarytype'];
      $notExpired = $params['notExpired'];

      // Search query building
      $query = [];

      if (strlen($recruiterId) > 0)
        $query['recruiter'] = new MongoId($recruiterId);
      if (strlen($companyId) > 0) {
        $query['company'] = new MongoId($companyId);
      } else if (strlen($industry) > 0) {
        $companies = self::findCompanyIdsByIndustry($industry);
        if (count($companies) > 0) $query['company'] = ['$in' => $companies];
      }

      // Filters
      if (!is_null($jobtype)) $query['jobtype'] = [ '$in' => $jobtype ];
      if (!is_null($salarytype)) {
        if (!is_array($salarytype) && strlen($salarytype) == 0) {
          $query['salarytype'] = '';
        } else {
          $salaryTypes = [];
          if (in_array('paid', $salarytype)) {
            $salaryTypes = array_merge($salaryTypes, [
              'month', 'week', 'day', 'hour', 'total'
            ]);
          }

          if (in_array('unpaid', $salarytype)) {
            $salaryTypes = array_merge($salaryTypes, [
              'commission', 'other'
            ]);
          }
          $query['salarytype'] = [ '$in' => $salaryTypes ];
        }
      }
      if (!is_null($notExpired) && $notExpired) {
        $query['$where'] =
          'function() { return new Date(this.deadline).getTime() > Date.now() }';
      }

      return $query;
    }

    // Appends the $near portion of the query.
    private static function buildSearchQueryNear(array $query, array $params) {
      $city = $params['city'];

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

      return $query;
    }

    // Appends the $text portion of the query.
    // Also applies an $in portion for the _id's returned from the near portion.
    private static function buildSearchQueryText(array $query,
                                                 array $params,
                                                 array $resNear) {
      $title = $params['title'];
      if (strlen($title) > 0) $query['$text'] = ['$search' => $title];

      $jobIds = [];
      foreach ($resNear as $job) {
        $jobId = $job['_id'];
        $jobIds[] = $jobId;
      }
      $query['_id'] = [ '$in' => $jobIds ];

      return $query;
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