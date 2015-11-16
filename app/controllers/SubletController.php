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
      $startdate = $data['startdate'];
      $enddate = $data['enddate'];
      $price = cleanfloat($data['price']);
      if (!isset($data['pricetype'])) $data['pricetype'] = '';
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
      $commented = isset($data['commented']);

      return array(
        'student' => $student, 'address' => $address, 'gender' => $gender,
        'city' => $city, 'state' => $state, 'geocode' => $geocode,
        'startdate' => $startdate, 'enddate' => $enddate, 'price' => $price,
        'title' => $title, 'summary' => $summary, 'occupancy' => $occupancy,
        'roomtype' => $roomtype, 'buildingtype' => $buildingtype,
        'photos' => $photos,
        'amenities' => $amenities, 'publish' => $publish,
        'comments' => $comments, 'commented' => $commented,
        'pricetype' => $pricetype
      );
    }

    function validateData($data, &$err) {
      $this->validate($data['pricetype'] != '',
        $err, 'must select a price type');
      $this->validate($data['price'] >= 0, $err, 'price cannot be negative');
      $this->validate($data['occupancy'] > 0,
        $err, 'occupancy must be positive');
      $this->validate(
        $data['enddate'] >= $data['startdate'],
        $err, 'invalid dates');
      $this->validate(count($data['photos']) >= 1,
        $err, 'must upload at least 1 photo');
    }

    function manage() {
      global $CStudent; $CStudent->requireLogin();
      global $MSublet;
      $data = array(
        'sublets' => $MSublet->getByStudent($_SESSION['_id'])
      );
      self::render('student/sublets/manage', $data);
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
        self::render('student/sublets/subletform', formData(array())); return;
      }

      global $params, $MSublet, $MStudent;
      $me = $MStudent->me();
      $params['student'] = $me['_id'];
      $params['publish'] = true;
      $params['comments'] = array();
      $params['startdate'] = strtotime($params['startdate']);
      $params['enddate'] = strtotime($params['enddate']);

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

      self::error($err);
      self::render('student/sublets/subletform', formData($this->formDataCommon($data)));
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
          self::render('student/sublets/subletform', formData(
            array_merge(
              $this->formDataCommon($this->data($entry)),
              array('_id' => $id)
            )
          )); return;
        }

        $params['publish'] = isset($params['publish']) ? true : false;
        $params['startdate'] = strtotime($params['startdate']);
        $params['enddate'] = strtotime($params['enddate']);

        extract($data = $this->data(array_merge($entry, $params)));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data = array_merge($entry, $data);
          $id = $MSublet->save($data);
          $this->success('sublet saved');
          self::render('student/sublets/subletform', formData(array_merge($this->formDataCommon($data), array('_id' => $id))));
          return;
        }
      }

      self::error($err);
      self::render('student/sublets/subletform', formData($data, array_merge($this->formDataCommon($data), array('_id' => $id))));
    }

    function view() {
      global $MSublet;
      global $MStudent;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and
        ($entry = $MSublet->get($id = $_GET['id'])) != NULL,
        $err, 'unknown sublet');
      if ($this->isValid())
        $this->validate(
          $entry['publish'] or (isset($_SESSION['_id']) and $entry['student'] == $_SESSION['_id']),
          $err, 'access denied');

      // Code
      if ($this->isValid()) {
        $data = array('commented' => false);

        if (isset($_POST['addcomment'])) {
          function dataComment($data) {
            $comment = clean($data['comment']);

            return array('comment' => $comment);
          }

          global $params;
          extract($data = dataComment($params));

          array_unshift($entry['comments'], array(
            'time' => time(),
            'commenter' => $_SESSION['_id'], 'comment' => $comment
          ));
          $data['commented'] = true;

          // Notify us of the comment
          $commenter = $_SESSION['email'];
          $message = "
            <b>$commenter</b> has commented on <a href=\"http://sublite.net/housing/sublet.php?id=$id\">$id</a>:
            <br /><br />
            $comment
          ";
          sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'Comment posted on SubLite!', $message);
        }

        $entry['stats']['views']++;
        $MSublet->save($entry);

        $data = array_merge($entry, $data);
        $data['_id'] = $entry['_id'];
        $data['mine'] = (isset($_SESSION['_id']) and $entry['student'] == $_SESSION['_id']);

        // ANY MODiFICATIONS ON DATA GOES HERE
        $s = $MStudent->getById($entry['student']);
        if ($s == NULL) {
          $entry['publish'] = false;
          $MSublet->save($entry);
          self::error('this listing is no longer available');
          self::render('notice');
          return;
        }

        $data['studentname'] = $s['name'];
        $data['studentid'] = $s['_id']->{'$id'};
        $data['studentclass'] = $s['class'] > 0 ?
          " '".substr($s['class'], -2) : '';
        $data['studentschool'] = strlen($s['school']) > 0 ?
          $s['school'] : 'Undergraduate';
        $data['studentpic'] = isset($s['photo']) ?
          $s['photo'] : $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
        global $S;
        $data['studentcollege'] = $S->nameOf($s['email']);
        $data['studentbio'] = isset($s['bio']) ?
          $s['bio'] : 'Welcome to my profile!';
        if(isset($_SESSION['loggedinstudent'])) {
          $me = $MStudent->me();
          $data['studentmsg'] =
            "Hi ".$data['studentname'].",%0A%0A".
            "I am writing to inquire about your listing '".$data['title']."' (http://sublite.net/housing/sublet.php?id=".$entry['_id'].").%0A%0A".
            "Best,%0A".
            $me['name'];
        }

        $data['latitude'] = $data['geocode']['latitude'];
        $data['longitude'] = $data['geocode']['longitude'];
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

        for ($i = 0; $i < count($data['comments']); $i ++) {
          $comment = $data['comments'][$i];
          $commenter = $MStudent->getById($comment['commenter']);
          $data['comments'][$i] = array(
            'name' => $commenter['name'],
            'photo' => $commenter['photo'],
            'time' => timeAgo($comment['time']),
            'text' => $comment['comment']
          );
        }

        self::displayMetatags('sublet');
        self::render('student/sublets/viewsublet', $data);
        return;
      }

      self::error($err);
      self::render('notice');
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
      // global $CStudent; $CStudent->requireLogin();

      global $params;
      $params = $_REQUEST;
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

        $sublet['summary'] = strmax($sublet['summary'], 100);

        $offset = 0.0001;
        $sublet['latitude'] = $sublet['geocode']['latitude'] + rand01() * $offset - $offset/2;
        $sublet['longitude'] = $sublet['geocode']['longitude'] + rand01() * $offset - $offset/2;

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
            usort($sublets, function ($a, $b) {
              if ($a['proximity'] < $b['proximity']) return -1;
              if ($a['proximity'] > $b['proximity']) return 1;
              return 0;
            });
            break;
        }

        return $sublets;
      }

      // Predefined searches
      $showSearch = true;

      if ($showSearch and !isset($params['search'])) {
        // If not searching for anything, then return last 6 entries
        $showMore = isset($_GET['showMore']);
        if ($showMore) {
          if (isset($_SESSION['showMore'])) $_SESSION['showMore'] += 6;
          else $_SESSION['showMore'] = 12;
          $showMore = $_SESSION['showMore'];
        } else $_SESSION['showMore'] = 6;

        $res = $MSublet->last($_SESSION['showMore']);
        $sublets = array();
        foreach ($res as $sublet) {
          $sublets[] = processRaw($sublet);
        }

        self::render('student/sublets/search/start', $this->dataSearchSetup());
        self::render('student/sublets/search/results', [
          'sublets' => $sublets,
          'recent' => true,
          'search' => 'housing',
          'showMore' => $showMore
        ]);
        return;
      }

      // Params to vars
      extract($data = $this->dataSearch(array_merge($this->dataSearchEmpty(), $params)));

      $this->startValidations();

      $this->validate(!is_null($geocode = geocode($location)), $err, 'invalid location or daily search limit reached (come back tomorrow)');
      if ($this->isValid()) {
        $latitude = $geocode['latitude'];
        $longitude = $geocode['longitude'];
        $startdate = strtotime($startdate);
        $enddate = strtotime($enddate);
        $maxProximity = $proximity == null ? 50 : (int)$proximity;
        $minPrice = (float)$price0;
        $maxPrice = (float)$price1;
        $minOccupancy = (int)$occupancy;

        // Validate parameters


        if ($this->isValid()) {
          // Search query building and validations
          $query = array(
            'publish' => true,
          );
          $proximityDeg = distanceDeg($maxProximity);
          $query['geocode.latitude'] = array(
            '$gte' => $latitude - $proximityDeg,
            '$lte' => $latitude + $proximityDeg
          );
          $query['geocode.longitude'] = array(
            '$gte' => $longitude - $proximityDeg,
            '$lte' => $longitude + $proximityDeg
          );
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

            $res = $MSublet->find($query);

            $sublets = array();
            $res = process($res, $sortby, $latitude, $longitude, $maxProximity);
            foreach ($res as $sublet) {
              $price = $sublet['price'];
              switch ($sublet['pricetype']) {
                case 'week': $price *= 4.35; break;
                case 'day': $price *= 30; break;
              }
              if (strlen($price0) > 0 and $price < $price0) continue;
              if (strlen($price1) > 0 and $price > $price1) continue;
              $sublets[] = $sublet;
            }

            $delay = round((microtime(true) - $starttime) * 1000, 0);

            self::render('student/sublets/search/results', [
              'sublets' => $sublets, 'delay' => $delay,
              'latitude' => $latitude, 'longitude' => $longitude,
              'maxProximity' => $maxProximity, 'showSearch' => $showSearch,
              'data' => $data, 'search' => 'housing'
            ]);

            // Send email notification of search to us
            // $this->sendrequestreport("Search for sublets:", $sublets);

            // Save search to db
            global $MApp;
            $MApp->recordSearch('sublets');

            return;
          }
        }
      }

      self::error($err);
      self::render('partials/subletsearchform', [
        'data' => $data,
        'search' => 'housing'
      ]);
    }
  }

  GLOBALvarSet('CSublet', new SubletController());
?>