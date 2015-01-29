<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class SubletController extends Controller {
    // TODO Decide some upper bound for duration
    function isValidDuration($duration) {
      if(!preg_match('`^[0-9]*$`', $duration)) return false;
      if(strlen($duration) > 5) return false;
      return intval($duration) > 0;
    }
      // TODO Should there be an upper bound for compensation?
    function isValidCompensation($compensation) {
      if(!preg_match('`^[0-9]*$`', $compensation)) return false;
      if(strlen($compensation) > 10) return false;
      return intval($compensation) > 0;
    }

    // TODO Check if date is today/after today
    // Q you should make sure 2000 char limit is displayed.
    function isValidDate($date) {
      // Check proper formatting
      if(!preg_match('`[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}`', $date)) {
        return false;
      }
      $ar = explode('/', $date);
      // Check if date exists
      if(!checkdate(intval($ar[0]), intval($ar[1]), intval($ar[2])))
        return false;
      // Check if date is in the future
      return strtotime($date) > time();
    }

    // TODO Decide on a good description length
    function isValidDescription($desc) {
      return strlen($desc) <= 2500;
    }

    // TODO? See if URL is actually valid
    function isValidURL($url) {
      // filter_var is pretty weak so we use other tests
      if (!preg_match('`^((https?:\/\/)*[\w\-]+\.[\w\-]+)`',
        $url)) return false;  

      return true;
    }

    function data($data) {
      $title = clean($data['title']);
      $Sublettype = clean($data['Sublettype']);
      $deadline = clean($data['deadline']);
      $duration = str2float(clean($data['duration']));
      $startdate = clean($data['startdate']);
      $enddate = clean($data['enddate']);
      $salarytype = clean($data['salarytype']);
      $salary = clean($data['salary']);
      if ($salarytype != 'other') $salary = str2float($salary);
      if ($Sublettype == 'fulltime') {
        $duration = '';
        $enddate = '';
      }
      $company = $data['company'];
      $desc = clean($data['desc']);
      $location = clean($data['location']);
      $locationtype = '';
      if(isset($data['locationtype'])) $locationtype = clean($data['locationtype']);
      $geocode = geocode($location);
      if ($locationtype) {
        $location = '';
        $geocode = '';
      }
      $requirements = clean($data['requirements']);
      $link = clean($data['link']);
      if (!preg_match('`^(https?:\/\/)`', $link)) $link = "http://$link";
      return array(
        'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
        'desc' => $desc, 'geocode' => $geocode,
        'location' => $location, 'requirements' => $requirements, 
        'link' => $link, 'salary' => $salary, 'company' => $company, 
        'salarytype' => $salarytype, 'startdate' => $startdate,
        'enddate' => $enddate, 'Sublettype' => $Sublettype,
        'locationtype' => $locationtype
      );
    }

    function validateData($data, &$err) {
      if (strlen($data['locationtype']) == 0) {
        $this->validate($data['geocode'] != NULL, $err, 'location invalid');
      }
      $this->validate(strlen($data['title']) <= 200,
        $err, 'Sublet title is too long');
      $this->validate(strlen(strval($data['salary'])) > 0,
        $err, 'please input numeric compensation/stipend amount');
      if ($data['Sublettype'] == 'internship') {
        $this->validate($data['duration'], $err, 'please input duration');
        $this->validate(!(!$data['startdate'] && $data['enddate']),
          $err, 'please also input a start date');
        if($data['startdate']) $this->validate($this->isValidDate($data['startdate']),
          $err, 'invalid start date: please check date');
        if($data['enddate']) $this->validate($this->isValidDate($data['enddate']),
          $err, 'invalid end date: please check date');
        if($data['startdate'] && $data['enddate']) {
          $this->validate(strtotime($data['enddate']) > strtotime($data['startdate']),
            $err, 'invalid date range: end date should be after start date.');
        }
      }
      else {
        if($data['startdate']) $this->validate($this->isValidDate($data['startdate']),
          $err, 'invalid start date: please check date');
      }
      // $this->validate($this->isValidDuration($data['duration']),
      //   $err, 'invalid duration');
      // $this->validate($this->isValidCompensation($data['salary']),
      //   $err, 'invalid compensation');
      $this->validate($this->isValidDate($data['deadline']),
        $err, 'invalid deadline: please check date');
      $this->validate(strtotime($data['deadline']) > time(),
        $err, 'invalid deadline: date should be in the future');
      $this->validate($this->isValidDescription($data['desc']),
        $err, 'description too long');
      $this->validate($this->isValidURL($data['link']),
        $err, 'invalid listing URL');
    }

    function manage() {
      global $CRecruiter; $CRecruiter->requireLogin();
      global $MSublet;
      $data = array(
        'Sublets' => $MSublet->getByRecruiter($_SESSION['_id'])
      );
      $this->render('manageSublets', $data);
    }

    function add() {
      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Sublet'));
      }

      global $CRecruiter; $CRecruiter->requireLogin();

      global $CCompany;
      if (!$CCompany->exists()) {
        $this->error('you must create a company profile first');
        $this->render('notice'); return;
      }

      if (!isset($_POST['add'])) { 
        $this->render('Subletform', formData(array())); return; 
      }
      
      global $params, $MSublet, $MRecruiter;
      $me = $MRecruiter->me();
      $params['company'] = $me['company'];

      $this->startValidations();
      $this->validate(isset($params['salarytype']), 
        $err, 'must select salary type');

      // Params to vars
      extract($data = $this->data($params));
      
      // Validations
      $this->validateData($data, $err);

      // Code
      if ($this->isValid()) {
        $data['applicants'] = array();
        $data['stats'] = array('views' => 0, 'clicks' => 0);
        $id = $MSublet->save($data);
        $this->redirect('Sublet', array('id' => $id));
        return;
      }
      
      $this->error($err);
      $this->render('Subletform', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      global $CRecruiter; $CRecruiter->requireLogin();
      
      global $params, $MSublet, $MRecruiter;
      // Params to vars
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and MongoId::isValid($id = $_GET['id']) and ($entry = $MSublet->get($id)) !== NULL, $err, 'unknown Sublet');
      if ($this->isValid())
        $this->validate($_SESSION['_id'] == $entry['recruiter'],
          $err, 'permission denied');

      // Code
      if ($this->isValid()) {
        function formData($data) {
          return array_merge($data, array(
            'headline' => 'Edit',
            'submitname' => 'edit', 'submitvalue' => 'Save Sublet'));
        }

        if (!isset($_POST['edit'])) { 
          $this->render('Subletform', formData(array_merge($this->data($entry), array('_id' => $id)))); return;
        }

        $me = $MRecruiter->me();
        $params['company'] = $me['company'];
        extract($data = $this->data($params));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data = array_merge($entry, $data);
          $id = $MSublet->save($data);
          $this->success('Sublet saved');
          $this->render('Subletform', formData(array_merge($data, array('_id' => $id))));
          return;
        }
      }
      
      $this->error($err);
      $this->render('Subletform', formData($data, array_merge($data, array('_id' => $id))));
    }
    
    function view() {
      //$this->requireLogin();
      global $MSublet;
      global $MRecruiter;
      global $MCompany;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MSublet->get($_GET['id'])) != NULL, 
        $err, 'unknown Sublet');

      // Code
      if ($this->isValid()) {
        $entry['stats']['views']++;
        $MSublet->save($entry, false);

        $data = $this->data($entry);
        $data['salarytype'] = ($data['salarytype'] == 'total') ?
                              $data['duration'].' weeks' : $data['salarytype'];
        
        $r = $MRecruiter->getById($entry['recruiter']);
        
        $company = $MCompany->get($entry['company']);
        // var_dump($entry);
        $data['companyname'] = $company['name'];
        $data['companybanner'] = $company['bannerphoto'];
        $data['companyid'] = $company['_id']->{'$id'};

        $data['recruitername'] = $r['firstname'] . ' ' . $r['lastname'];
        $data['recruiterid'] = $r['_id']->{'$id'};

        $this->render('viewSublet', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }


    function requireLogin() {
      global $CRecruiter, $CStudent;
      if ($CRecruiter->loggedIn()) $CRecruiter->requireLogin();
      else $CStudent->requireLogin();
    }

    function dataSearchSetup() {
      global $MApp;
      return array('industries' => 
        array_merge(array(''), $MApp->getIndustriesBySublets())
      );
    }
    function dataSearchEmpty() {
      return array('recruiter' => '', 'company' => '', 'title' => '', 
                   'industry' => '', 'city' => '');
    }
    function dataSearch($data) {
      $recruiter = clean($data['recruiter']);
      $company = clean($data['company']);
      $title = clean($data['title']);
      $industry = clean($data['industry']);
      $city = clean($data['city']);

      return array_merge($this->dataSearchSetup(), array(
        'recruiter' => $recruiter, 'company' => $company, 'title' => $title,
        'industry' => $industry, 'city' => $city
      ));
    }

    function search() {
      $this->requireLogin();

      global $params;
      global $MSublet, $MStudent, $MCompany, $MRecruiter;

      // Function for processing results and showing them
      function process($res) {
        global $MCompany;
        // Processing results
        $Sublets = array();
        foreach ($res as $Sublet) {
          $company = $MCompany->get($Sublet['company']);
          $Sublet['company'] = $company['name'];
          if (strlen($Sublet['desc']) > 300) {
            $Sublet['desc'] = substr($Sublet['desc'], 0, 297) . '...';
          }
          $Sublet['logophoto'] = $company['logophoto'];
          array_push($Sublets, $Sublet);
        }
        return $Sublets;
      }

      // Predefined searches
      $showSearch = true;
      $showCompany = null;
      if (isset($_GET['recruiter'])) {
        $params = $this->dataSearchEmpty();
        $params['recruiter'] = $_GET['recruiter'];
        $showSearch = false;
      }
      if (isset($_GET['company'])) {
        $params = $this->dataSearchEmpty();
        $params['company'] = $_GET['company'];
        $showCompany = $MCompany->getByName($_GET['company']);
        $showSearch = false;
      }

      if ($showSearch and !isset($_POST['search'])) {
        // If not searching for anything, then return last 5 entries
        $res = $MSublet->last(5);
        $Sublets = process($res);

        $this->render('searchform', $this->dataSearchSetup());
        $this->render('searchresults', array('Sublets' => $Sublets, 'recent' => true));
        return; 
      }
      
      // Params to vars
      extract($data = $this->dataSearch($params));

      // Validations
      $this->startValidations();
      $this->validate(strlen($recruiter) == 0 or 
                      !is_null($MRecruiter->getByID($recruiter)),
        $err, 'unknown recruiter');

      // Code
      if ($this->isValid()) {

        // Searching for companies
        $companyquery = array();

        if (strlen($company) > 0) {
          $companyquery['name'] = array('$regex' => keywords2mregex($company));
        }
        if (strlen($industry) > 0) {
          $companyquery['industry'] = array('$regex' => keywords2mregex($industry));
        }
        if (strlen($city) > 0) {
          $companyquery['location'] = array('$regex' => keywords2mregex($city));
        }
        $cs = $MCompany->find($companyquery);

        // Search query building
        $query = array();

        if (strlen($recruiter) > 0) 
          $query['recruiter'] = new MongoId($recruiter);
        
        if (strlen($title) > 0) {
          $query['title'] = array('$regex' => keywords2mregex($title));
        }

        $companies = array();
        foreach ($cs as $c) {
          array_push($companies, $c['_id']);
        }
        $query['company'] = array('$in' => $companies);

        // Performing search
        $res = $MSublet->find($query);
        $Sublets = process($res);

        if ($showSearch) $this->render('searchform', $data);
        $this->render('searchresults', array('Sublets' => $Sublets, 'showCompany' => $showCompany));
        return;
      }

      $this->error($err);
      $this->render('searchform', $data);
    }
  }
  $CSublet = new SubletController();
?>