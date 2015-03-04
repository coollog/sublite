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

    function save($data) {
      $this->collection->save($data);
      return $data['_id'];
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => $id));
    }

    function countRecruiters() {
      return $this->db->recruiters->count();
    }
    function countJobListings() {
      return $this->db->jobs->count();
    }
    function getJobsMissingRecruiter() {
      $jobs = $this->db->jobs->find();

      global $MRecruiter;

      $norecruiter = array();
      foreach ($jobs as $job) {
        if (!$MRecruiter->IDexists($job['recruiter'])) {
          $norecruiter[] = $job;
        }
      }

      return $norecruiter;
    }
    function countCompanies() {
      return $this->db->companies->count();
    }
    function getIndustries() {
      $industries = $this->db->companies->find(array(), array('industry' => 1));
      $is = array();
      foreach ($industries as $i) {
        $is[] = $i['industry'];
      }
      return $is;
    }
    function getIndustriesByJobs() {
      global $MCompany;

      $industries = array();
      $jobs = $this->db->jobs->find();
      foreach ($jobs as $job) {
        $industry = $MCompany->getIndustry($job['company']);
        $industrysplit = explode(',', $industry);
        foreach ($industrysplit as $i) {
          $i = trim($i);
          if (strlen($i) > 0 and !in_array($i, $industries))
            $industries[] = $i;
        }
      }
      natsort($industries);

      return $industries;
    }
    function countSubletListings() {
      return $this->dbstudent->listings->count();
    }
    function countStudents() {
      return $this->dbstudent->emails->count();
    }
    function getStudentsConfirmed() {
      return $this->dbstudent->emails->find(array(
        'pass' => array('$exists' => true)
      ), array('email' => true));
    }
    function getStudentsUnconfirmed() {
      return $this->dbstudent->emails->find(array(
        'pass' => array('$exists' => false)
      ), array('email' => true));
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
      require_once($GLOBALS['dirpre'].'../housing/schools.php');
      return count($S->LUT);
    }

    function recordSearch($type) {
      $entry = $this->get('searches');
      $entry[] = array(
        'type' => $type,
        'searcher' => $_SESSION['email'],
        'data' => $_REQUEST
      );
      $this->save($entry);
    }
  }

  $MStats = new StatsModel();

?>