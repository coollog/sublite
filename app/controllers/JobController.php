<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/ApplicationJob.php');

  interface JobControllerInterface {
    public static function delete(array $restOfRoute);
    public static function search();
  }

  class JobController extends Controller implements JobControllerInterface {
    public static function delete(array $restOfRoute) {
      RecruiterController::requireLogin();

      $jobId = self::getIdFromRoute($restOfRoute);
      if (is_null($jobId)) return;

      // Make sure job exists.
      // Make sure recruiter has permission to edit the job.
      if (!self::checkJobExists($jobId)) return;
      if (!self::ownsJob($jobId)) return;

      // Delete all applications with jobid.
      ApplicationModel::deleteByJob($jobId);

      // Delete from questions' uses this jobid.
      $application = ApplicationJob::get($jobId);
      $questions = [];
      if (!is_null($application)) {
        $questions = $application->getQuestions();
      }
      foreach ($questions as $questionId) {
        QuestionModel::removeFromUses($questionId, $jobId);
      }

      // Delete this job.
      JobModel::deleteById($jobId);

      // Redirect back to home.
      self::redirect('../home');
    }

    public static function portal() {
      self::render('jobs/portal', [
        ]);
    }

    public static function search() {
      // Predefined searches
      $showSearch = true;
      $showCompany = null;
      $recruiterId = null;
      $recruiterName = null;
      $companyId = null;
      if (isset($_GET['byrecruiter'])) {
        $recruiterId = $_GET['byrecruiter'];
        $recruiterName = RecruiterModel::getName($recruiterId);
        $showSearch = false;
      }
      if (isset($_GET['bycompany'])) {
        $companyId = $_GET['bycompany'];
        $showCompany = CompanyModel::getById(new MongoId($_GET['bycompany']));
        $showSearch = false;
      }

      self::render('jobs/search/main', [
        'showSearch' => $showSearch,
        'showCompany' => $showCompany,
        'recruiterId' => $recruiterId,
        'recruiterName' => $recruiterName
      ]);
      self::render('jobs/search/form', [
        'showSearch' => $showSearch,
        'recruiterId' => $recruiterId,
        'companyId' => $companyId,
        'industries' => array_merge([''], AppModel::getIndustriesByJobs())
      ]);
      self::render('jobs/search/results', ['recent' => $showSearch]);
    }

    /**
     * Checks if $jobId is a job, and if not, error.
     */
    private static function checkJobExists(MongoId $jobId) {
      if (!JobModel::exists($jobId)) {
        self::error("nonexistent job");
        self::render('notice');
        return false;
      }
      return true;
    }

    /**
     * Checks if the recruiters owns $jobId, and if not, error.
     */
    private static function ownsJob(MongoId $jobId) {
      $recruiterId = $_SESSION['_id'];
      if (!JobModel::matchJobRecruiter($jobId, $recruiterId)) {
        self::error("permission denied");
        self::render('notice');
        return false;
      }
      return true;
    }

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

    function hasEmail($desc) {
      $matches = [];
      $pattern = '/[A-Za-z0-9_-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/';
      return preg_match($pattern, $desc, $matches);
    }

    function data($data) {
      $title = clean($data['title']);
      $jobtype = clean($data['jobtype']);
      $deadline = clean($data['deadline']);
      $duration = str2float(clean($data['duration']));
      $startdate = clean($data['startdate']);
      $enddate = clean($data['enddate']);
      $salarytype = isset($data['salarytype']) ? clean($data['salarytype']) : '';
      $salary = clean($data['salary']);
      if ($salarytype != 'other' && $salarytype != 'commission')
        $salary = str2float($salary);
      if ($jobtype == 'fulltime' || $jobtype == 'parttime') {
        $duration = '';
        $enddate = '';
      }
      $company = $data['company'];
      $desc = clean($data['desc']);
      $location = [];
      if (is_array($data['location'])) {
        foreach ($data['location'] as $loc) {
          if ($loc) $location[] = clean($loc);
        }
      }
      $locationtype = '';
      if(isset($data['locationtype'])) $locationtype = clean($data['locationtype']);
      $geocodes = [];
      $geoJSON = new GeoMultiPoint();
      foreach ($location as $loc) {
        $geocode = Geocode::geocode($loc);
        $geocodes[] = $geocode;
        $geoJSON->append($geocode['latitude'], $geocode['longitude']);
      }
      if ($locationtype) {
        $location = '';
        $geocodes = '';
      }
      $requirements = clean($data['requirements']);

      $data = [
        'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
        'desc' => $desc,
        'geocodes' => $geocodes,
        'location' => $location, 'requirements' => $requirements,
        'salary' => $salary, 'company' => $company,
        'salarytype' => $salarytype, 'startdate' => $startdate,
        'enddate' => $enddate, 'jobtype' => $jobtype,
        'locationtype' => $locationtype
      ];

      if (!$locationtype) $data['geoJSON'] = $geoJSON->toArray();

      return $data;
    }

    function validateData($data, &$err) {
      if (strlen($data['locationtype']) == 0) {
        foreach ($data['geocodes'] as $geocode) {
          self::validate($geocode != NULL, $err, 'location invalid');
        }
      }
      self::validate(count($data['location']) > 0,
                     $err, 'job location is required');
      self::validate(strlen($data['title']) <= 200,
                     $err, 'job title is too long');
      self::validate(strlen(strval($data['salary'])) > 0,
        $err, 'please input numeric compensation/stipend amount');
      if ($data['jobtype'] == 'internship') {
        self::validate($data['duration'], $err, 'please input duration');
        self::validate(!(!$data['startdate'] && $data['enddate']),
          $err, 'please also input a start date');
        if($data['startdate']) self::validate(self::isValidDate($data['startdate']),
          $err, 'invalid start date: please check date');
        if($data['enddate']) self::validate(self::isValidDate($data['enddate']),
          $err, 'invalid end date: please check date');
        if($data['startdate'] && $data['enddate']) {
          self::validate(strtotime($data['enddate']) > strtotime($data['startdate']),
            $err, 'invalid date range: end date should be after start date.');
        }
      }
      else {
        if($data['startdate']) self::validate(self::isValidDate($data['startdate']),
          $err, 'invalid start date: please check date');
      }
      // self::validate(self::isValidDuration($data['duration']),
      //   $err, 'invalid duration');
      // self::validate(self::isValidCompensation($data['salary']),
      //   $err, 'invalid compensation');
      self::validate(self::isValidDate($data['deadline']),
        $err, 'invalid deadline: please check date');
      self::validate(strtotime($data['deadline']) > time(),
        $err, 'invalid deadline: date should be in the future');
      self::validate(self::isValidDescription($data['desc']),
        $err, 'description too long');
      $this->validate(!$this->hasEmail($data['desc']),
        $err, 'please do not include emails in your description');
      $this->validate(!$this->hasEmail($data['desc']),
        $err, 'please do not include emails in your description');
      $this->validate(!$this->hasEmail($data['requirements']),
        $err, 'please do not include emails in your requirements');
    }

    function manage() {
      RecruiterController::requireLogin();

      $jobs = JobModel::getByRecruiter($_SESSION['_id']);
      $data = [ 'jobs' => $jobs ];
      self::render('jobs/managejobs', $data);
    }

    function add() {
      RecruiterController::requireLogin();

      function formData(array $data) {
        return array_merge($data, [
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Job'
        ]);
      }

      if (!RecruiterModel::hasCompany()) {
        self::error('you must create a company profile first');
        self::render('notice'); return;
      }

      if (!isset($_POST['add'])) {
        self::render('jobs/jobform', formData([
          'location' => []
        ])); return;
      }

      global $params;
      $me = RecruiterModel::me();
      $params['company'] = $me['company'];

      self::startValidations();
      self::validate(isset($params['salarytype']),
                     $err, 'must select salary type');

      // Params to vars
      extract($data = self::data($params));

      // Validations
      self::validateData($data, $err);

      // Code
      if (self::isValid()) {
        $data['applicants'] = [];
        $data['viewers'] = [];
        $data['stats'] = [ 'views' => 0, 'clicks' => 0 ];
        $jobId = JobModel::save($data);

        // Add credit for adding job.
        $recruiterId = $_SESSION['_id'];
        $credsBefore = RecruiterModel::getCredits($recruiterId);
        RecruiterModel::addCreditsForNewJob($recruiterId);
        // Make sure recruiter actually got free credit.
        if (RecruiterModel::getCredits($recruiterId) == $credsBefore) {
          $msg = "$recruiterId did not receive free credit.";
          sendgmail(['tony.jiang@yale.edu', 'qingyang.chen@gmail.com'],
            'info@sublite.net', 'Recruiter did not receive free credit for job.', $msg);
        }

        self::redirect("editapplication/$jobId");
        // This should go after the application form is set up.
        // self::redirect('job', array('id' => $jobId));
        return;
      }

      self::error($err);
      self::render('jobs/jobform', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      RecruiterController::requireLogin();

      global $params, $MJob, $MRecruiter;
      // Params to vars

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and MongoId::isValid($id = $_GET['id']) and ($entry = $MJob->get($id)) !== NULL, $err, 'unknown job');
      if ($this->isValid())
        $this->validate($_SESSION['_id'] == $entry['recruiter'],
          $err, 'permission denied');

      function formData($data) {
        return array_merge($data, [
          'headline' => 'Edit',
          'submitname' => 'edit', 'submitvalue' => 'Save Job']);
      }

      // Code
      if ($this->isValid()) {
        if (!isset($_POST['edit'])) {
          self::render('jobs/jobform',
                       formData(array_merge($this->data($entry),
                                            [ '_id' => $id ])));
          return;
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
          self::render('jobs/jobform', formData(array_merge($data, [ '_id' => $id ])));
          return;
        }
      }

      $this->error($err);
      self::render('jobs/jobform', formData(array_merge($data, [ '_id' => $id ])));
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
      if (RecruiterController::loggedIn()) RecruiterController::requireLogin();
      else StudentController::requireLogin();
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
      $title = clean($data['title']);
      $industry = clean($data['industry']);
      $city = clean($data['city']);

      return array_merge(self::dataSearchSetup(), [
        'recruiter' => $recruiter, 'title' => $title,
        'industry' => $industry, 'city' => $city
      ]);
    }

    // Function for processing results and showing them
    function processSearchResults($res) {
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
  }

  GLOBALvarSet('CJob', new JobController());
?>