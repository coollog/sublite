<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class StatsModel extends Model {
    private $dbstudent, $db;

    function __construct() {
      // Setup database
      $m = new MongoClient($GLOBALS['dburistudent']);
      $this->dbstudent = $m->$GLOBALS['dbnamestudent'];
      $m = new MongoClient($GLOBALS['dburi']);
      $this->db = $m->$GLOBALS['dbname'];
    }

    function countRecruiters() {
      return $this->db->recruiters->count();
    }
    function countJobListings() {
      return $this->db->jobs->count();
    }
    function countCompanies() {
      return $this->db->companies->count();
    }
    function countSubletListings() {
      return $this->dbstudent->listings->count();
    }
    function countStudents() {
      return $this->dbstudent->emails->count();
    }
    function countCities() {
      $jobs = $this->db->jobs->find();
      $sublets = $this->dbstudent->listings->find();

      $cities = array();

      function addCity(&$cities, $doc) {
        if (isset($doc['city']) and isset($doc['state']))
          $city = getCity($doc['city'] . ', ' . $doc['state']);
        else
          $city = getCity($doc['location']);
        if ($city != null) {
          if (!in_array($city, $cities))
            array_push($cities, $city);
        }
      }
      foreach ($sublets as $doc) {
        addCity($cities, $doc);
      }
      foreach ($jobs as $doc) {
        addCity($cities, $doc);
      }

      return count($cities);
    }
    function countUniversities() {
      require_once($GLOBALS['dirpre'].'../schools.php');
      return count($S->LUT);
    }

  }

  $MStats = new StatsModel();

?>