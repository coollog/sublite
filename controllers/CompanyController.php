<?php
  require_once('controllers/Controller.php');

  class CompanyController extends Controller {
    function data($data) {
        $name = $data['name'];
        $industry = clean($data['industry']);
        $desc = clean($data['desc']);
        return array(
          'name' => $name, 'industry' => $industry, 'desc' => $desc
        );
    }
    function validateData($data, &$err) {
      global $MRecruiter;
      $me = $MRecruiter->me();
      $this->validate($data['name'] == $me['company'],
        $err, 'location invalid');
    }

    function add() {
      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Company'));
      }

      global $CRecruiter; $CRecruiter->requireLogin();
      
      global $params, $MCompany, $MRecruiter;

      $me = $MRecruiter->me();
      if (!isset($_POST['add'])) { 
        $this->render('companyform', formData(array(
          'name' => $me['company']))); return; 
      }
      // Params to vars      
      $params['name'] = $me['company'];
      extract($data = $this->data($params));
      
      // Validations
      $this->startValidations();
      $this->validate(!MongoId::isValid($me['company']), 
        $err, 'company exists');
      $this->validate(strlen($data['desc']) <= 2000,
        $err, 'description is too long'); // Figure out good char limits
      $this->validate(strlen($data['industry'] <= 100),
        $err, 'industry too long'); //Figure out good char limits

      // Code
      if ($this->isValid()) {
        $id = $MCompany->save($data);
        $me = $MRecruiter->me();
        $me['company'] = new MongoID($id);
        $MRecruiter->save($me);
        $this->redirect('home');
        return;
      }
      
      $this->error($err);
      $this->render('companyform', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      global $CRecruiter; $CRecruiter->requireLogin();
      
      global $params, $MCompany, $MRecruiter;
      // Params to vars
      $me = $MRecruiter->me();
      $id = $params['_id'] = $me['company'];
      if (!MongoId::isValid($me['company'])) $this->redirect('addcompany');

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MCompany->get($me['company'])) !== NULL, 
        $err, 'unknown company');

      // Code
      if ($this->isValid()) {
        function formData($data) {
          return array_merge($data, array(
            'headline' => 'Edit',
            'submitname' => 'edit', 'submitvalue' => 'Save Company'));
        }

        if (!isset($_POST['edit'])) { 
          $this->render('companyform', formData($this->data($entry))); return;
        }

        $params['name'] = $entry['name'];
        extract($data = $this->data($params));
        // Validations

        if ($this->isValid()) {
          $data['_id'] = new MongoId($id);
          $id = $MCompany->save($data);
          $this->success('company saved');
          $this->render('companyform', formData($data));
          return;
        }
      }
      
      $this->error($err);
      $this->render('notice');
    }
    
    function view() {
      global $CRecruiter; $CRecruiter->requireLogin();
      global $MCompany;

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MCompany->get($me['company'])) !== NULL, 
        $err, 'unknown company');

      // Code
      if ($this->isValid()) {
        $this->render('viewcompany', $this->data($entry));
        return;
      }

      $this->error($err);
      $this->render('notice');
    }
  }

  $CCompany = new CompanyController();

?>