<?php
  require_once('controllers/Controller.php');

  class JobController extends Controller {
    function home() {

    }

    function add() {
      global $CRecruiter; $CRecruiter->requireLogin();
      if (!isset($_POST['add'])) { $this->render('addjob'); return; }
      
      global $params, $MJob;
      // Params to vars
      $title = clean($params['title']);
      $deadline = clean($params['deadline']);
      $duration = clean($params['duration']);
      $desc = clean($params['desc']);
      $funfacts = clean($params['funfacts']);
      $photo = clean($params['photo']);
      $location = clean($params['location']);
      $requirements = clean($params['requirements']);
      $data = array(
        'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
        'desc' => $desc, 'funfacts' => $funfacts, 'photo' => $photo,
        'location' => $location, 'requirements' => $requirements
      );
      
      // Validations
      $this->startValidations();

      // Code
      if ($this->isValid()) {
        $id = $MJob->save($data);
        $this->redirect('job', array('id' => $id));
        return;
      }
      
      echo $err; // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('addjob', $data);
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
        if (!isset($_POST['edit'])) { 
          $this->render('editjob', $MJob->data($entry)); return;
        }

        $title = clean($params['title']);
        $deadline = clean($params['deadline']);
        $duration = clean($params['duration']);
        $desc = clean($params['desc']);
        $funfacts = clean($params['funfacts']);
        $photo = clean($params['photo']);
        $location = clean($params['location']);
        $requirements = clean($params['requirements']);
        $data = array(
          'title' => $title, 'deadline' => $deadline, 'duration' => $duration,
          'desc' => $desc, 'funfacts' => $funfacts, 'photo' => $photo,
          'location' => $location, 'requirements' => $requirements
        );
        // Validations


        if ($this->isValid()) {
          $id = $MJob->save($data);
          echo 'job saved';
          $this->render('editjob', $data);
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
        $this->render('viewjob', $MJob->data($entry));
        return;
      }

      echo $err;
    }
  }

  $CJob = new JobController();

?>