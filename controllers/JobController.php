<?php
  require_once('controllers/Controller.php');

  class JobController extends Controller {
    function data($data) {
        $title = clean($data['title']);
        $deadline = clean($data['deadline']);
        $duration = clean($data['duration']);
        $salary = clean($data['salary']);
        $desc = clean($data['desc']);
        $location = clean($data['location']);
        $requirements = clean($data['requirements']);
        $link = clean($data['link']);
        return array(
          'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
          'desc' => $desc,
          'location' => $location, 'requirements' => $requirements, 
          'link' => $link, 'salary' => $salary
        );
    }

    function home() {

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
      
      global $params, $MJob;
      // Params to vars
      extract($data = $this->data($params));
      
      // Validations
      $this->startValidations();

      // Code
      if ($this->isValid()) {
        $id = $MJob->save($data);
        $this->redirect('job', array('id' => $id));
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('jobform', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      global $CRecruiter; $CRecruiter->requireLogin();
      
      global $params, $MJob;
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

        extract($data = $this->data($params));
        // Validations


        if ($this->isValid()) {
          $data['_id'] = new MongoId($id);
          $id = $MJob->save($data);
          echo 'job saved';
          $this->render('jobform', formData($data));
          return;
        }
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
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
        $this->render('viewjob', $this->data($entry));
        return;
      }

      echo $err;
    }
  }

  $CJob = new JobController();

?>