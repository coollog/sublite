<?php
  require_once('controllers/Controller.php');

  class JobController extends Controller {
    function data($data) {
        $title = clean($data['title']);
        $deadline = clean($data['deadline']);
        $duration = clean($data['duration']);
        $salary = clean($data['salary']);
        $salarytype = clean($data['salarytype']);
        $company = $data['company'];
        $desc = clean($data['desc']);
        $location = clean($data['location']);
        $geocode = geocode($location);
        $requirements = clean($data['requirements']);
        $link = clean($data['link']);
        return array(
          'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
          'desc' => $desc, 'geocode' => $geocode,
          'location' => $location, 'requirements' => $requirements, 
          'link' => $link, 'salary' => $salary, 'company' => $company, 
          'salarytype' => $salarytype
        );
    }

    function validateData($data, &$err) {
      $this->validate(MongoId::isValid($data['company']), 
        $err, 'company invalid');
      $this->validate($data['geocode'] != NULL, $err, 'location invalid');
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
      $this->validate(isset($_GET['id']) and ($entry = $MJob->get($id = $_GET['id'])) !== NULL, $err, 'unknown job');
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
          $this->render('jobform', formData($this->data($entry))); return;
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
          $this->render('jobform', formData($data));
          return;
        }
      }
      
      $this->error($err);
      $this->render('notice');
    }
    
    function view() {
      global $CRecruiter; $CRecruiter->requireLogin();
      global $MJob;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MJob->get($_GET['id'])) != NULL, 
        $err, 'unknown job');

      // Code
      if ($this->isValid()) {
        $data = $this->data($entry);
        $data['salarytype'] = ($data['salarytype'] == 'total') ?
                              $data['duration'] : $data['salarytype'];
        $this->render('viewjob', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }
  }

  $CJob = new JobController();

?>