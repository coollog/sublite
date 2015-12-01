<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationJob.php');

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
      if (filter_var($url, FILTER_VALIDATE_EMAIL)) {
        return true;
      }
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
      if ($salarytype != 'other') $salary = str2float($salary);
      if ($jobtype == 'fulltime' || $jobtype == 'parttime') {
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

      return array(
        'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
        'desc' => $desc, 'geocode' => $geocode,
        'location' => $location, 'requirements' => $requirements,
        'salary' => $salary, 'company' => $company,
        'salarytype' => $salarytype, 'startdate' => $startdate,
        'enddate' => $enddate, 'jobtype' => $jobtype,
        'locationtype' => $locationtype
      );
    }

    function validateData($data, &$err) {
      if (strlen($data['locationtype']) == 0) {
        $this->validate($data['geocode'] != NULL, $err, 'location invalid');
      }
      $this->validate(strlen($data['title']) <= 200,
        $err, 'job title is too long');
      $this->validate(strlen(strval($data['salary'])) > 0,
        $err, 'please input numeric compensation/stipend amount');
      if ($data['jobtype'] == 'internship') {
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
    }

    function manage() {
      global $CRecruiter; $CRecruiter->requireLogin();
      global $MJob;

      $jobs = $MJob->getByRecruiter($_SESSION['_id']);
      $data = array(
        'jobs' => $jobs
      );
      self::render('jobs/managejobs', $data);
    }

    function add() {
      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Job'));
      }

      global $CRecruiter; $CRecruiter->requireLogin();

      global $CCompany;
      if (!$CCompany->exists()) {
        $this->error('you must create a company profile first');
        self::render('notice'); return;
      }

      if (!isset($_POST['add'])) {
        self::render('jobs/jobform', formData(array())); return;
      }

      global $params, $MJob, $MRecruiter;
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
        $data['viewers'] = array();
        $data['stats'] = array('views' => 0, 'clicks' => 0);
        $jobId = $MJob->save($data);

        // Add credit for adding job.
        $recruiterId = $_SESSION['_id'];
        RecruiterModel::addCreditsForNewJob($recruiterId);

        $this->redirect("editapplication/$jobId");
        // This should go after the application form is set up.
        // $this->redirect('job', array('id' => $jobId));
        return;
      }

      $this->error($err);
      self::render('jobs/jobform', formData($data));
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

      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Edit',
          'submitname' => 'edit', 'submitvalue' => 'Save Job'));
      }

      // Code
      if ($this->isValid()) {
        if (!isset($_POST['edit'])) {
          self::render('jobs/jobform', formData(array_merge($this->data($entry), array('_id' => $id)))); return;
        }

        $me = $MRecruiter->me();
        $params['company'] = $me['company'];
        extract($data = $this->data($params));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data = array_merge($entry, $data);
          $id = $MJob->save($data);
          $this->success('job saved');
          self::render('jobs/jobform', formData(array_merge($data, array('_id' => $id))));
          return;
        }
      }

      $this->error($err);
      self::render('jobs/jobform', formData($data, array_merge($data, array('_id' => $id))));
    }

    function view() {
      //$this->requireLogin();
      global $MJob;
      global $MRecruiter;
      global $MCompany;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and
        ($entry = $MJob->get($_GET['id'])) != NULL,
        $err, 'unknown job');

      // Code
      if ($this->isValid()) {
        $entry['stats']['views']++;
        if(isset($_SESSION['loggedinstudent'])) {
          $entry['viewers'][] = array($_SESSION['_id'], new MongoDate());
        }
        else {
          $entry['viewers'][] = array('', new MongoDate());
        }
        $MJob->save($entry, false);

        $data = $entry;

        $data['hasApplication'] = isset($data['application']);

        $data['_id'] = $entry['_id'];
        $data['salarytype'] = ($data['salarytype'] == 'total') ?
                              $data['duration'].' weeks' : $data['salarytype'];

        $r = $MRecruiter->getById($entry['recruiter']);

        $company = $MCompany->get($entry['company']);

        $data['companyname'] = $company['name'];
        $data['companybanner'] = $company['bannerphoto'];
        $data['companyid'] = $company['_id']->{'$id'};

        $data['recruitername'] = $r['firstname'] . ' ' . $r['lastname'];
        $data['recruiterid'] = $r['_id']->{'$id'};

        self::displayMetatags('job');
        self::render('jobs/viewjob', $data);
        return;
      }

      $this->error($err);
      self::render('notice');
    }


    function requireLogin() {
      global $CRecruiter, $CStudent;
      if ($CRecruiter->loggedIn()) $CRecruiter->requireLogin();
      else $CStudent->requireLogin();
    }

    function dataSearchSetup() {
      global $MApp;
      return array('industries' =>
        array_merge(array(''), $MApp->getIndustriesByJobs())
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
      // $this->requireLogin();

      global $params;
      $params = $_REQUEST;
      global $MJob, $MStudent, $MCompany, $MRecruiter;

      // Function for processing results and showing them
      function process($res) {
        global $MCompany;
        // Processing results
        $jobs = array();
        foreach ($res as $job) {
          $company = $MCompany->get($job['company']);
          $job['company'] = $company['name'];
          $job['desc'] = strmax($job['desc'], 300);
          $job['logophoto'] = $company['logophoto'];
          array_push($jobs, $job);
        }
        return $jobs;
      }

      // Predefined searches
      $showSearch = true;
      $showCompany = null;
      if (isset($_GET['byrecruiter'])) {
        $params = $this->dataSearchEmpty();
        $params['recruiter'] = $_GET['byrecruiter'];
        $showSearch = false;
      }
      if (isset($_GET['bycompany'])) {
        $params = $this->dataSearchEmpty();
        $params['company'] = $_GET['bycompany'];
        $showCompany = $MCompany->getByName($_GET['bycompany']);
        $showSearch = false;
      }

      if ($showSearch and !isset($params['search'])) {
        // If not searching for anything, then return last 6 entries
        $showMore = isset($_GET['showMore']);
        if ($showMore) {
          if (isset($_SESSION['showMoreJobs'])) $_SESSION['showMoreJobs'] += 6;
          else $_SESSION['showMoreJobs'] = 12;
          $showMore = $_SESSION['showMoreJobs'];
        } else $_SESSION['showMoreJobs'] = 6;

        $res = $MJob->last($_SESSION['showMoreJobs']);
        $jobs = process($res);
        $recent = count($jobs) < JobModel::getSize();

        self::render('jobs/search/form', $this->dataSearchSetup());
        self::render('jobs/search/results', array('jobs' => $jobs, 'recent' => $recent, 'search' => 'jobs', 'showMore' => $showMore));
        return;
      }

      // Params to vars
      extract($data = $this->dataSearch($params));

      // Validations
      $this->startValidations();
      $this->validate(!MongoId::isValid($recruiter) or
                      !is_null(RecruiterModel::getById(new MongoId($recruiter))),
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
        $res = $MJob->find($query);
        $jobs = process($res);

        if ($showSearch) self::render('jobs/search/form', $data);
        self::render('jobs/search/results', array('jobs' => $jobs, 'showCompany' => $showCompany, 'search' => 'jobs'));

        // Send email notification of search to us
        // $this->sendrequestreport("Search for jobs:", $jobs);

        // Save search to db
        global $MApp;
        $MApp->recordSearch('jobs');

        return;
      }

      $this->error($err);
      self::render('jobs/search/form', array_merge($data, array('search' => 'jobs')));
    }
  }

  GLOBALvarSet('CJob', new JobController());
?>