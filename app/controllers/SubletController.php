<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class SubletController extends Controller {
    function data($data) {
      $student = $data['student'];
      $address = clean($data['address']);
      $gender = clean($data['gender']);
      $city = clean($data['city']);
      $state = clean($data['state']);
      $geocode = geocode($data['geocode']);
      $startdate = clean($data['startdate']);
      $enddate = clean($data['enddate']);
      $price = clean($data['price']);
      $title = clean($data['title']);
      $summary = clean($data['summary']);
      $occupancy = clean($data['occupancy']);
      $room = clean($data['room']);
      $buildingtype = clean($data['buildingtype']);
      $imgs = array();
      if (isset($data['img'])) {
        foreach ($data['img'] as $img)
          $imgs[] = clean($img);
      }
      $amenities = array();
      if (isset($data['amenity'])) {
        foreach ($data['amenity'] as $amenity)
          $amenities[] = clean($amenity);
      }
      $publish = $data['publish'];
      $comments = $data['comments'];

      return array(
        'student' => $student, 'address' => $address, 'gender' => $gender,
        'city' => $city, 'state' => $state, 'geocode' => $geocode, 
        'startdate' => $startdate, 'enddate' => $enddate, 'price' => $price,
        'title' => $title, 'summary' => $summary, 'occupancy' => $occupancy,
        'room' => $room, 'buildingtype' => $buildingtype, 'imgs' => $imgs,
        'amenities' => $amenities, 'publish' => $publish, 
        'comments' => $comments
      );


      // MIGRATIONS NEEDED:
      // - change location to address
      // - change N and W to just geocode object with also "location_type": "APPROXIMATE"
      // - change from to startdate and to to enddate
      // - change occ to occupancy
      // - change building to buildingtype
    }

    function validateData($data, &$err) {
      // WRITE VALIDATEDATA
    }

    function manage() {
      // WRITE MANAGE
    }

    function add() {
      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Sublet'));
      }

      global $CStudent; $CStudent->requireLogin();

      if (!isset($_POST['add'])) { 
        $this->render('subletform', formData(array())); return;
      }
      
      global $params, $MSublet, $MStudent;
      $me = $MStudent->me();
      $params['student'] = $me['_id'];
      $params['publish'] = true;
      $params['comments'] = array();

      // Params to vars
      extract($data = $this->data($params));
      
      // Validations
      $this->startValidations();
      $this->validateData($data, $err);

      // Code
      if ($this->isValid()) {
        $data['stats'] = array('views' => 0, 'clicks' => 0);
        $id = $MSublet->save($data);
        $this->redirect('sublet', array('id' => $id));
        return;
      }
      
      $this->error($err);
      $this->render('subletform', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      global $CStudent; $CStudent->requireLogin();
      
      global $params, $MSublet, $MStudent;
      // Params to vars
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and MongoId::isValid($id = $_GET['id']) and ($entry = $MSublet->get($id)) !== NULL, $err, 'unknown Sublet');
      if ($this->isValid())
        $this->validate($_SESSION['_id'] == $entry['Student'],
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

        $me = $MStudent->me();
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
      global $MStudent;
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
        
        $r = $MStudent->getById($entry['Student']);
        
        $company = $MCompany->get($entry['company']);
        // var_dump($entry);
        $data['companyname'] = $company['name'];
        $data['companybanner'] = $company['bannerphoto'];
        $data['companyid'] = $company['_id']->{'$id'};

        $data['Studentname'] = $r['firstname'] . ' ' . $r['lastname'];
        $data['Studentid'] = $r['_id']->{'$id'};

        $this->render('viewSublet', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }


    function requireLogin() {
      global $CStudent, $CStudent;
      if ($CStudent->loggedIn()) $CStudent->requireLogin();
      else $CStudent->requireLogin();
    }

    function dataSearchSetup() {
      global $MApp;
      return array('industries' => 
        array_merge(array(''), $MApp->getIndustriesBySublets())
      );
    }
    function dataSearchEmpty() {
      return array('Student' => '', 'company' => '', 'title' => '', 
                   'industry' => '', 'city' => '');
    }
    function dataSearch($data) {
      $Student = clean($data['Student']);
      $company = clean($data['company']);
      $title = clean($data['title']);
      $industry = clean($data['industry']);
      $city = clean($data['city']);

      return array_merge($this->dataSearchSetup(), array(
        'Student' => $Student, 'company' => $company, 'title' => $title,
        'industry' => $industry, 'city' => $city
      ));
    }

    function search() {
      $this->requireLogin();

      global $params;
      global $MSublet, $MStudent, $MCompany, $MStudent;

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
      if (isset($_GET['Student'])) {
        $params = $this->dataSearchEmpty();
        $params['Student'] = $_GET['Student'];
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
      $this->validate(strlen($Student) == 0 or 
                      !is_null($MStudent->getByID($Student)),
        $err, 'unknown Student');

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

        if (strlen($Student) > 0) 
          $query['Student'] = new MongoId($Student);
        
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