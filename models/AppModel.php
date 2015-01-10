<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class AppModel extends Model {
    function __construct() {
      parent::__construct('app');
    }

    function save($data) {
      $this->collection->save($data);
      return $data['_id'];
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => $id));
    }

    function updateStats($countCities=false) {
      global $MStats;

      if ($countCities) $cities = $MStats->countCities();
      else {
        $doc = $this->getStats();
        $cities = $doc['cities'];
      }

      $this->save($stats = array(
        '_id' => 'stats',
        'recruiters' => $MStats->countRecruiters(),
        'jobs' => $MStats->countJobListings(),
        'companies' => $MStats->countCompanies(),
        'sublets' => $MStats->countSubletListings(),
        'students' => $MStats->countStudents(),
        'universities' => $MStats->countUniversities(),
        'cities' => $cities
      ));
      return $stats;
    }

    function getStats() {
      global $MStats;
      return $this->get('stats');
    }
  }

  $MApp = new AppModel();

?>