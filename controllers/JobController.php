<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class JobController extends Controller {
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
      $jobtype = clean($data['jobtype']);
      $deadline = clean($data['deadline']);
      $duration = str2float(clean($data['duration']));
      $startdate = clean($data['startdate']);
      $enddate = clean($data['enddate']);
      $salarytype = clean($data['salarytype']);
      $salary = clean($data['salary']);
      if ($salarytype != "other") $salary = str2float($salary);
      if ($jobtype == "fulltime") {
        $duration = "";
        $enddate = "";
      }
      $company = $data['company'];
      $desc = clean($data['desc']);
      $location = clean($data['location']);
      $locationtype = "";
      if(isset($data['locationtype'])) $locationtype = clean($data['locationtype']);
      $geocode = geocode($location);
      if ($locationtype) {
        $location = "";
        $geocode = "";
      }
      $international = clean($data['international']);
      $requirements = clean($data['requirements']);
      $link = clean($data['link']);
      if (!preg_match('`^(https?:\/\/)`', $link)) $link = "http://$link";
      return array(
        'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
        'desc' => $desc, 'geocode' => $geocode,
        'location' => $location, 'requirements' => $requirements, 
        'link' => $link, 'salary' => $salary, 'company' => $company, 
        'salarytype' => $salarytype, 'startdate' => $startdate,
        'enddate' => $enddate, 'jobtype' => $jobtype,
        'locationtype' => $locationtype, 'international' => $international
      );
    }

    function validateData($data, &$err) {
      if (!$data['locationtype']) {
        $this->validate($data['geocode'] != NULL, $err, 'location invalid');        
      }
      $this->validate(strlen($data['title']) <= 200,
        $err, 'job title is too long');
      $this->validate(strlen($data['salary']) > 0,
        $err, 'please input numeric compensation/stipend amount');
      if ($data['jobtype'] == "internship") {
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
      global $MJob;
      $data = array(
        'jobs' => $MJob->getByRecruiter($_SESSION['_id'])
      );
      $this->render('managejobs', $data);
    }

    function add() {
      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Job'));
      }

      global $CRecruiter; $CRecruiter->requireLogin();
      if (!isset($_POST['add'])) { 
        $this->render('jobform', formData(array())); return; 
      }
      
      global $params, $MJob, $MRecruiter;
      $me = $MRecruiter->me();
      $params['company'] = $me['company'];
      // Params to vars
      extract($data = $this->data($params));
      
      // Validations
      $this->startValidations();
      $this->validateData($data, $err);

      // Code
      if ($this->isValid()) {
        $data['applicants'] = array();
        $id = $MJob->save($data);
        $this->redirect('job', array('id' => $id));
        return;
      }
      
      $this->error($err);
      $this->render('jobform', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      global $CRecruiter; $CRecruiter->requireLogin();
      
      global $params, $MJob, $MRecruiter;
      // Params to vars
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and MongoId::isValid($id = $_GET['id']) and ($entry = $MJob->get($id)) !== NULL, $err, 'unknown job');
      if ($this->isValid())
        $this->validate($_SESSION['_id'] == $entry['recruiter'],
          $err, 'permission denied');

      // Code
      if ($this->isValid()) {
        function formData($data) {
          return array_merge($data, array(
            'headline' => 'Edit',
            'submitname' => 'edit', 'submitvalue' => 'Save Job'));
        }

        if (!isset($_POST['edit'])) { 
          $this->render('jobform', formData(array_merge($this->data($entry), array('_id' => $id)))); return;
        }

        $me = $MRecruiter->me();
        $params['company'] = $me['company'];
        extract($data = $this->data($params));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data['_id'] = new MongoId($id);
          $id = $MJob->save($data);
          $this->success('job saved');
          $this->render('jobform', formData(array_merge($data, array('_id' => $id))));
          return;
        }
      }
      
      $this->error($err);
      $this->render('jobform', formData($data, array_merge($data, array('_id' => $id))));
    }
    
    function view() {
      global $CRecruiter; $CRecruiter->requireLogin();
      global $MJob;
      global $MRecruiter;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MJob->get($_GET['id'])) != NULL, 
        $err, 'unknown job');

      // Code
      if ($this->isValid()) {
        $data = $this->data($entry);
        $data['salarytype'] = ($data['salarytype'] == 'total') ?
                              $data['duration'].' weeks' : $data['salarytype'];
        $company = $MRecruiter->getCompany($entry['recruiter']);
        $data['companyname'] = $company['name'];
        $data['companybanner'] = $company['bannerphoto'];
        $data['companyid'] = $company['_id']->{'$id'};
        $this->render('viewjob', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }



    function dataSearch($data) {
      $recruiter = clean($data['recruiter']);
      $company = clean($data['company']);
      $title = clean($data['title']);
      return array(
        'recruiter' => $recruiter, 'company' => $company, 'title' => $title
      );
    }

    function search() {
      global $CRecruiter, $CStudent;
      $CStudent->requireLogin();
      if ($CRecruiter->loggedIn()) $CRecruiter->requireLogin();
      else $CStudent->requireLogin();

      if (!isset($_POST['search'])) { 
        $this->render('searchform'); return; 
      }
      
      global $params, $MJob, $MStudent, $MCompany;
      // Params to vars
      extract($data = $this->dataSearch($params));

      // Validations
      $this->startValidations();
      $this->validate(strlen($recruiter) == 0 or 
                      !is_null($MRecruiter->getByID($recruiter)),
        $err, 'unknown recruiter');
      $cs = $MCompany->find(array('name' => array('$regex' => keywords2mregex($company))));
      $this->validate(strlen($company) == 0 or
                      $cs->count() > 0,
        $err, 'unknown company');

      // Code
      if ($this->isValid()) {
        $query = array();

        // Search query building
        if (strlen($recruiter) > 0) 
          $query['recruiter'] = new MongoId($recruiter);
        if (strlen($company) > 0) {
          
          $companies = array();
          foreach ($cs as $c) {
            array_push($companies, $c['_id']);
          }
          $query['company'] = array('$in' => $companies);
        }
        if (strlen($title) > 0) {
          $query['title'] = array('$regex' => keywords2mregex($title));
        }

        // Performing search
        $res = $MJob->find($query);

        // Processing results
        $jobs = array();
        foreach ($res as $job) {
          $job['company'] = $MCompany->getName($job['company']);
          array_push($jobs, $job);
        }

        $this->render('searchform', $data);
        $this->render('searchresults', array('jobs' => $jobs));
        return;
      }

      $this->error($err);
      $this->render('searchform', $data);
    }
  }
  $CJob = new JobController();
?>