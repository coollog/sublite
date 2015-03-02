<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class SubletController extends Controller {
    function data($data) {
      $student = $data['student'];
      $address = clean($data['address']);
      $gender = clean($data['gender']);
      if ($gender == 'both') $gender = '';
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
        $data['enddate'] >= $data['startdate'],
        $err, 'invalid dates');
      $this->validate(count($data['photos']) >= 4, 
        $err, 'must upload at least 4 photos');
    }

    function manage() {
      global $CStudent; $CStudent->requireLogin();
      global $MSublet;
      $data = array(
        'sublets' => $MSublet->getByStudent($_SESSION['_id'])
      );
      $this->render('managesublets', $data);
    }

    function formDataCommon($data) {
      $data['startdate'] = fdate($data['startdate']);
      $data['enddate'] = fdate($data['enddate']);
      return $data;
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
      $this->render('subletform', formData($this->formDataCommon($data)));
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
        return array_merge($data, array(
          'headline' => 'Edit',
          'submitname' => 'edit', 'submitvalue' => 'Save Sublet'));
      }

      // Code
      if ($this->isValid()) {
        if (!isset($_POST['edit'])) { 
          $this->render('subletform', formData(
            array_merge(
              $this->formDataCommon($this->data($entry)),
              array('_id' => $id)
            )
          )); return;
        }

        $params['publish'] = isset($params['publish']) ? true : false;

        extract($data = $this->data(array_merge($entry, $params)));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data = array_merge($entry, $data);
          $id = $MSublet->save($data);
          $this->success('sublet saved');
          $this->render('subletform', formData(array_merge($this->formDataCommon($data), array('_id' => $id))));
          return;
        }
      }
      
      $this->error($err);
      $this->render('subletform', formData($data, array_merge($this->formDataCommon($data), array('_id' => $id))));
    }
    
    function view() {
      global $MSublet;
      global $MStudent;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
        ($entry = $MSublet->get($_GET['id'])) != NULL, 
        $err, 'unknown sublet');
      $this->validate(
        $entry['publish'] or $entry['student'] == $_SESSION['_id'], 
        $err, 'access denied');

      // Code
      if ($this->isValid()) {
        $entry['stats']['views']++;
        $MSublet->save($entry);

        $data = $this->data($entry);
        
        // ANY MODiFICATIONS ON DATA GOES HERE
        if(isset($_SESSION['loggedinstudent'])) {
          $me = $MStudent->me();
        }

        $s = $MStudent->getById($entry['student']);

        $data['studentname'] = $s['name'];
        $data['studentid'] = $s['_id']->{'$id'};
        $data['studentclass'] = $s['class'] > 0 ? 
          " '".substr($s['class'], -2) : '';
        $data['studentschool'] = strlen($s['school']) > 0 ?
          $s['school'] : 'Undergraduate';
        $data['studentpic'] = isset($s['photo']) ?
          $s['photo'] : $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
        require_once($GLOBALS['dirpre'].'../housing/schools.php');
        $data['studentcollege'] = $S->nameOf($s['email']);
        $data['studentbio'] = isset($s['bio']) ?
          $s['bio'] : 'Welcome to my profile!';
        if(isset($_SESSION['loggedinstudent'])) {
          $data['studentmsg'] = 
            "Hi ".$data['studentname'].",%0A%0A".
            "I am writing to inquire about your listing '".$data['title']."' (http://sublite.net/housing/sublet.php?id=".$entry['_id'].").%0A%0A".
            "Best,%0A".
            $me['name'];
        }
        
        $data['address'] = 
          $data['address'].', '.$data['city'].', '.$data['state'];
        if (count($data['photos']) == 0)
          $data['photos'][] = $GLOBALS['dirpre'].'assets/gfx/subletnophoto.png';
        $data['startdate'] = fdate($data['startdate']);
        $data['enddate'] = fdate($data['enddate']);
        switch ($data['gender']) {
          case 'male': $data['gender'] = 'Male only'; break;
          case 'female': $data['gender'] = 'Female only'; break;
        }

        $this->render('viewsublet', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }

    function dataSearchSetup() {
      /* CONVERT */
      return array();
    }
    function dataSearchEmpty() {
      /* MAKE GENDER THE GENDER OF THE USER */
      global $MStudent;
      $me = $MStudent->me();
      $gender = $me['gender'];

      return array(
        'location' => '', 'startdate' => '', 'enddate' => '', 'price0' => '',
        'price1' => '', 'people' => '', 'roomtype' => 'Any',
        'buildingtype' => 'Any', 'occupancy' => '', 'sortby' => '',
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
      $gender = $data['gender'];
      $amenities = array();
      for ($i = 0; $i < count($data['amenities']); $i ++) {
        $amenities[$i] = clean($data['amenities'][$i]);
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

      // process without sorting/filtering
      function processRaw($sublet) {
        // Processing result
        $sublet['photo'] = isset($sublet['photos'][0]) ? $sublet['photos'][0]
          : $GLOBALS['dirpre'].'assets/gfx/subletnophoto.png';

        $sublet['address'] = $sublet['address'];
        if (strlen($sublet['city']) > 0)
          $sublet['address'] .= ', '.$sublet['city'];
        if (strlen($sublet['state']) > 0)
          $sublet['address'] .= ', '.$sublet['state'];

        $sublet['proximity'] = isset($sublet['proximity']) ? $sublet['proximity'] : null;

        return $sublet;
      }

      // Function for processing results and showing them
      function process($res, $sortby, $latitude, $longitude, $maxProximity) {
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
          $sublet['proximity'] = distance($latitude, $longitude, $sublet['geocode']['latitude'], $sublet['geocode']['longitude']);
          if ($maxProximity == 0 or $sublet['proximity'] <= $maxProximity) {
            $sublets[] = processRaw($sublet);
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
        // If not searching for anything, then return last 6 entries
        $res = $MSublet->last(6);
        $sublets = array();
        foreach ($res as $sublet) {
          $sublets[] = processRaw($sublet);
        }

        $this->render('subletsearchstart', $this->dataSearchSetup());
        $this->render('subletsearchresults', array('sublets' => $sublets, 'recent' => true));
        return; 
      }
      
      // Params to vars
      extract($data = $this->dataSearch(array_merge($this->dataSearchEmpty(), $params)));

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
          // Search query building and validations
          $query = array(
            'publish' => true,
          );
          if ($maxProximity == 0) $maxProximity = 50;
          $proximityDeg = distanceDeg($maxProximity);
          $query['geocode.latitude'] = array(
            '$gte' => $latitude - $proximityDeg,
            '$lte' => $latitude + $proximityDeg
          );
          $query['geocode.longitude'] = array(
            '$gte' => $longitude - $proximityDeg,
            '$lte' => $longitude + $proximityDeg
          );
          if (strlen($price0) > 0) {
            $query['price']['$gte'] = $minPrice;
          }
          if (strlen($price1) > 0) {
            $query['price']['$lte'] = $maxPrice;
          }
          if (strlen($occupancy) > 0) {
            $query['occupancy'] = array('$gte' => $minOccupancy);
          }
          if ($roomtype != 'Any') {
            $query['roomtype'] = $roomtype;
          }
          if ($buildingtype != 'Any') {
            $query['buildingtype'] = $buildingtype;
          }
          if ($gender != '' and $gender != 'other') {
            $query['gender'] = array('$in' => array('', $gender));
          }
          if (count($amenities) > 0) {
            $query['amenities'] = array('$all' => $amenities);
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

            $querymonth = array_merge($query, array('pricetype' => 'month'));
            if (strlen($price0) > 0) $querymonth['price']['$gte'] *= 4.35;
            if (strlen($price1) > 0) $querymonth['price']['$lte'] *= 4.35;
            $queryday = array_merge($query, array('pricetype' => 'day'));
            if (strlen($price0) > 0) $queryday['price']['$gte'] /= 30;
            if (strlen($price1) > 0) $queryday['price']['$lte'] /= 30;
            $resmonth = $MSublet->find($querymonth);
            $resweek = $MSublet->find($query);
            $resday = $MSublet->find($queryday);

            if (isset($_GET['test'])) {
              var_dump($query);
            }

            $sublets = array_merge(
              process($resmonth, $sortby, $latitude, $longitude, $maxProximity),
              process($resweek, $sortby, $latitude, $longitude, $maxProximity),
              process($resday, $sortby, $latitude, $longitude, $maxProximity)
            );

            $delay = round((microtime(true) - $starttime) * 1000, 0);

            if ($showSearch) $this->render('subletsearchform', $data);
            $this->render('subletsearchresults', array(
              'sublets' => $sublets, 'delay' => $delay
            ));
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