<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface AppModelInterface {
    public static function getByStringId($id);
  }

  class AppModel extends Model implements AppModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public static function getByStringId($id) {
      return self::$collection->findOne(array('_id' => $id));
    }

    function __construct() {
      parent::__construct(self::DB_TYPE, 'app');

      // Set up indexes.
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id'];
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

    static function getStats() {
      return self::getByStringId('stats');
    }
    function getIndustries() {
      $stats = $this->getStats();
      return $stats['industries'];
    }
    function getIndustriesByJobs() {
      $stats = $this->getStats();
      return $stats['industriesbyjobs'];
    }

    function getSearches() {
      return $this->get('searches');
    }

    function recordSearch($type) {
      if (!isset($_SESSION['loggedinstudent'])) return;

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

    protected static $collection;
  }

  GLOBALvarSet('MApp', new AppModel());
?>