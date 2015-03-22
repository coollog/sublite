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
        'industries' => $MStats->getIndustries(),
        'industriesbyjobs' => $MStats->getIndustriesByJobs(),
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
    function getIndustries() {
      $stats = $this->getStats();
      return $stats['industries'];
    }
    function getIndustriesByJobs() {
      $stats = $this->getStats();
      return $stats['industriesbyjobs'];
    }

    function recordSearch($type) {
      $email = $_SESSION['email'];
      $data = $_REQUEST;

      if (($entry = $this->get('searches')) == NULL)
        $entry = array();
      $entry['_id'] = 'searches';
      $entry[time()] = array(
        'email' => $email,
        'type' => $type,
        'data' => $data
      );

      $this->save($entry);
    }
  }

  $MApp = new AppModel();

?>