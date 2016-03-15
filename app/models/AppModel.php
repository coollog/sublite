<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class AppModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct() {
      parent::__construct(self::DB_TYPE, 'app');
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id'];
    }

    static function get($id) {
      return self::$collection->findOne(array('_id' => $id));
    }

    function updateStats($countCities=false) {
      global $MStats;

      if ($countCities) $cities = $MStats->countCities();
      else {
        $doc = self::getStats();
        $cities = $doc['cities'];
      }

      self::save($stats = array(
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

    static function getStats() {
      return self::get('stats');
    }
    function getIndustries() {
      $stats = $this->getStats();
      return $stats['industries'];
    }
    function getIndustriesByJobs() {
      $stats = self::getStats();
      return $stats['industriesbyjobs'];
    }

    function getSearches() {
      return $this->get('searches');
    }

    function recordSearch($type) {
      if (!isset($_SESSION['loggedinstudent'])) return;

      $email = $_SESSION['email'];
      $data = $_REQUEST;

      if (($entry = self::get('searches')) == NULL) $entry = [];
      $entry['_id'] = 'searches';
      $entry[time()] = [
        'email' => $email,
        'type' => $type,
        'data' => $data
      ];

      self::save($entry);
    }

    protected static $collection;
  }

  GLOBALvarSet('MApp', new AppModel());
?>