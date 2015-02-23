<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class SubletController extends Controller {
    function data($data) {
      $student = $data['student'];
      $address = clean($data['address']);
      $gender = clean($data['gender']);
      $city = clean($data['city']);
      $state = clean($data['state']);
      $geocode = geocode("$address, $city, $state");
      $startdate = strtotime($data['startdate']);
      $enddate = strtotime($data['enddate']);
      $price = clean($data['price']);
      $pricetype = clean($data['pricetype']);
      $title = clean($data['title']);
      $summary = clean($data['summary']);
      $occupancy = clean($data['occupancy']);
      $roomtype = clean($data['roomtype']);
      $buildingtype = clean($data['buildingtype']);
      $photos = array();
      if (isset($data['photos'])) {
        foreach ($data['photos'] as $photo)
          $photos[] = clean($photo);
      }
      $amenities = array();
      if (isset($data['amenities'])) {
        foreach ($data['amenities'] as $amenity)
          $amenities[] = clean($amenity);
      }
      $publish = $data['publish'];
      $comments = $data['comments'];

      return array(
        'student' => $student, 'address' => $address, 'gender' => $gender,
        'city' => $city, 'state' => $state, 'geocode' => $geocode, 
        'startdate' => $startdate, 'enddate' => $enddate, 'price' => $price,
        'title' => $title, 'summary' => $summary, 'occupancy' => $occupancy,
        'roomtype' => $roomtype, 'buildingtype' => $buildingtype, 
        'photos' => $photos,
        'amenities' => $amenities, 'publish' => $publish, 
        'comments' => $comments, 'pricetype' => $pricetype
      );
    }

    function validateData($data, &$err) {
      $this->validate($data['price'] >= 0, $err, 'price cannot be negative');
      $this->validate($data['occupancy'] > 0,
        $err, 'occupancy must be positive');
      $this->validate(
        strtotime($data['enddate']) >= strtotime($data['startdate']),
        $err, 'invalid dates');
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
        $data['stats'] = array('views' => 0);
        $id = $MSublet->save($data);
        $this->redirect('sublet', array('id' => $id));
        return;
      }
      
      $this->error($err);
      $this->render('subletform', formData($data));
    }

    function edit() {
      global $CStudent; $CStudent->requireLogin();
      
      global $params, $MSublet, $MStudent;
      // Params to vars
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and MongoId::isValid($id = $_GET['id']) and ($entry = $MSublet->get($id)) !== NULL, $err, 'unknown sublet');
      if ($this->isValid())
        $this->validate($_SESSION['_id'] == $entry['student'],
          $err, 'permission denied');

      function formData($data) {
        $data['startdate'] = fdate($data['startdate']);
        $data['enddate'] = fdate($data['enddate']);
        return array_merge($data, array(
          'headline' => 'Edit',
          'submitname' => 'edit', 'submitvalue' => 'Save Sublet'));
      }

      // Code
      if ($this->isValid()) {
        if (!isset($_POST['edit'])) { 
          $this->render('subletform', formData(array_merge($this->data($entry), array('_id' => $id)))); return;
        }

        extract($data = $this->data(array_merge($entry, $params)));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data = array_merge($entry, $data);
          $id = $MSublet->save($data);
          $this->success('sublet saved');
          $this->render('subletform', formData(array_merge($data, array('_id' => $id))));
          return;
        }
      }
      
      $this->error($err);
      $this->render('subletform', formData($data, array_merge($data, array('_id' => $id))));
    }
    
    function view() {
      global $MSublet;
      global $MStudent;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MSublet->get($_GET['id'])) != NULL, 
        $err, 'unknown sublet');

      // Code
      if ($this->isValid()) {
        $entry['stats']['views']++;
        $MSublet->save($entry);

        $data = $this->data($entry);
        
        // ANY MODiFICATIONS ON DATA GOES HERE
        $s = $MStudent->getById($entry['student']);

        $data['studentname'] = $s['name'];
        $data['studentid'] = $s['_id']->{'$id'};
        $data['studentclass'] = $s['class'] > 0 ? 
          " '".substr($s['class'], -2) : '';
        $data['studentschool'] = strlen($s['school']) > 0 ?
          $data['school'] : 'Undergraduate';
        $data['studentpic'] = isset($s['pic']) ?
          $dta['pic'] : $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
        require_once($GLOBALS['dirpre'].'../housing/schools.php');
        $data['studentcollege'] = $S->nameOf($s['email']);
        $data['studentbio'] = isset($s['bio']) ?
          $data['bio'] : 'Welcome to my profile!';

        $data['address'] = 
          $data['address'].', '.$data['city'].', '.$data['state'];
        $data['mainphoto'] = $data['photos'][0];
        $data['startdate'] = fdate($data['startdate']);
        $data['enddate'] = fdate($data['enddate']);

        $this->render('viewsublet', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }

    function dataSearchSetup() {
      /* CONVERT */
      global $MApp;
      return array('industries' => 
        array_merge(array(''), $MApp->getIndustriesBySublets())
      );
    }
    function dataSearchEmpty() {
      /* MAKE GENDER THE GENDER OF THE USER */
      $gender = '';

      return array(
        'location' => '', 'startdate' => '', 'enddate' => '', 'price0' => '',
        'price1' => '', 'people' => '', 'roomtype' => 'Any',
        'buildingtype' => 'Any',
        'gender' => $gender, 'amenities' => array(), 'proximity' => ''
      );
    }
    function dataSearch($data) {
      $location = clean($data['location']);
      $startdate = clean($data['startdate']);
      $enddate = clean($data['enddate']);
      $price0 = clean($data['price0']);
      $price1 = clean($data['price1']);
      $occupancy = clean($data['occupancy']);
      $roomtype = clean($data['roomtype']);
      $buildingtype = clean($data['buildingtype']);
      $gender = clean($data['gender']);
      $amenities = array();
      for ($i = 0; $i < count($amenities); $i ++) {
        $amenities[$i] = clean($amenities[$i]);
      }
      $proximity = clean($data['proximity']);
      $sortby = clean($data['sortby']);

      return array_merge($this->dataSearchSetup(), array(
        'location' => $location, 'startdate' => $startdate, 
        'enddate' => $enddate, 'price0' => $price0, 'price1' => $price1,
        'occupancy' => $occupancy, 'roomtype' => $roomtype,
        'buildingtype' => $buildingtype, 'gender' => $gender,
        'amenities' => $amenities, 'proximity' => $proximity,
        'sortby' => $sortby
      ));
    }
    function validateSearch($query, &$err) {
      /* CONVERT */
      //$this->validate($query[''] == null)
    }

    function search() {
      global $CStudent; $CStudent->requireLogin();

      global $params;
      global $MSublet, $MStudent;

      // Function for processing results and showing them
      function process($res, $sortby, $latitude, $longitude) {
        $sublets = array();

        // Sort
        switch ($sortby) {
          case 'priceIncreasing':
            $res->sort(array('price' => 1));
            break;
          case 'priceDecreasing':
            $res->sort(array('price' => -1));
            break;
        }
        foreach ($res as $sublet) {
          $sublet['proximity'] = distance($latitude, $longitude, $sublet['latitude'], $sublet['longitude']);
          if ($sublet['proximity'] <= $maxProximity) {
            // Processing result
            $sublet['img'] = isset($sublet['imgs'][0]) ? 
                             $sublet['imgs'][0] : 'assets/gfx/defaultpic.png';
            
            $sublets[] = $sublet;
          }
        }
        switch ($sortby) {
          case 'proximityIncreasing':
            function sorter($a, $b) {
              if ($a['proximity'] < $b['proximity']) return -1;
              if ($a['proximity'] > $b['proximity']) return 1;
              return 0;
            }
            usort($sublets, 'sorter');
            break;
        }
        
        return $sublets;
      }

      // Predefined searches
      $showSearch = true;

      if ($showSearch and !isset($_POST['search'])) {
        // If not searching for anything, then return last 5 entries
        $res = $MSublet->last(5);
        $sublets = process($res);

        $this->render('subletsearchform', $this->dataSearchSetup());
        $this->render('subletsearchresults', array('sublets' => $sublets, 'recent' => true));
        return; 
      }
      
      // Params to vars
      extract($data = $this->dataSearch($params));

      $this->startValidations();

      $this->validate(!is_null($geocode = geocode($location)), $err, 'invalid location');
      if ($this->isValid()) {
        $latitude = $geocode['latitude'];
        $longitude = $geocode['longitude'];
        $startdate = strtotime($startdate);
        $enddate = strtotime($enddate);
        $maxProximity = (int)$proximity;
        $minPrice = (float)$price0;
        $maxPrice = (float)$price1;
        $minOccupancy = (int)$occupancy;

        // Validate parameters


        if ($this->isValid()) {
          if (strlen($title) > 0) {
            $query['title'] = array('$regex' => keywords2mregex($title));
          }
          // Search query building and validations
          $query = array(
            'publish' => true,
            'price' => array('$gte' => $minPrice, '$lte' => $maxPrice),
            'occupancy' => array('$gte' => $minOccupancy)
          );
          if ($roomtype != 'Any') {
            $query['roomtype'] = $roomtype;
          }
          if ($buildingtype != 'Any') {
            $query['buildingtype'] = $buildingtype;
          }
          if ($gender != 'Any') {
            $query['gender'] = $gender;
          }
          if (count($amenities) > 0) {
            $query['amenities'] = array('$in' => $amenities);
          }
          if (strlen($startdate) > 0) {
            $query['startdate'] = array('$lte' => $startdate);
          }
          if (strlen($enddate) > 0) {
            $query['enddate'] = array('$gte' => $enddate);
          }

          // Validate query
          $this->validateSearch($query, $err);

          if ($this->isValid()) {
            // Performing search
            $starttime = microtime(true);
            $res = $MSublet->find($query);
            $delay = microtime(true) - $starttime;

            $sublets = process($res, $sortby, $latitude, $longitude);

            if ($showSearch) $this->render('subletsearchform', $data);
            $this->render('subletsearchresults', array('sublets' => $sublets));
            return;
          }
        }
      }

      $this->error($err);
      $this->render('subletsearchform', $data);
    }
  }
  $CSublet = new SubletController();
?>