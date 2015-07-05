<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class SocialController extends Controller {

    function index() {
      if (isset($_POST['signup'])) {
        global $CStudent, $MStudent;
        $CStudent->requireLogin();

        // Params to vars
        global $params;
        $city = clean($params['city']);
        
        // Validations
        $this->startValidations();

        // Code
        if ($this->isValid()) {
          $me = $MStudent->me();
          $me['hubs'] = array(
            'city' => $city
          );
          $MStudent->save($me);

          $email = $_SESSION['email'];
          $message = "
            <h1>Sign Up for Social Hubs</h1><br />
            <b>Email:</b> $email<br />
            <b>City:</b> $city
          ";
          sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'Social Hub Sign Up', $message);

          $this->success('Thanks for signing up! We will notify you when our social hubs feature is ready to use! Stay tuned!');
          $this->render('socialindex', array('hubs' => true, 'signedup' => true));
          return;
        }
        
        $this->error($err);
      }

      $vdata = array(
        'hubs' => true
      );

      global $MStudent;
      $me = $MStudent->me();
      if (isset($me['hubs']['myhub'])) {
        $vdata['myhub'] = $me['hubs']['myhub'];
      }

      $this->render('socialindex', $vdata);
    }

    function hub() {
      if (!isset($_GET['id']))
        $this->redirect('start');

      $hubid = $_GET['id'];

      $this->render('socialhub', array(
        'hub' => $hubid
      ));
    }
    function admin() {
      $this->render('socialadmin');
    }

    function checkIsSet($message, $fields, &$var) {
      foreach ($fields as $f) {
        if(!isset($message[$f])){
          $var = $this->errorString("$f not set");
          return false;
        }
      }
      return true;
    }

    function isValidDate($date) {
      //TODO
    }

    function errorString($err) {
      $json = array(
        'status' => 'fail',
        'data' => '',
        'message' => $err
      );
      return json_encode($json);
    }

    function successString($data = "", $message = "") {
      $json = array(
        'status' => 'success',
        'data' => $data,
        'message' => $message
      );
      return json_encode($json);
    }

    function api() {
      global $CStudent, $MStudent, $MSocial, $S;
      $kill = false;
      if ($kill) {
        return;
      }
      $name = $_POST['name'];
      $json = $_POST['json'];
      $message = $json; // json_decode($json, true);

      // make sure logged in
      if (!$CStudent->loggedIn()) {
        return $this->errorString('you must be logged in');
      }

      // clean data
      foreach ($message as $key => $val) {
        $message[$key] = clean($message[$key]);
      }
      
      $reterr = "";

      // make sure id, pass, and hub are set in $message
      if (!$this->checkIsSet($message, array('hub'), $reterr)) {
        return $reterr;
      }
      $hub = $message['hub'];
      $id = $_SESSION['_id'];

      // make sure hub exists
      if (!$MSocial->get($hub)) {
        return $this->errorString("hub doesn't exist");
      }

      // specific stuff
      $success = $this->successString();
      switch ($name) {
        /* 
         *
         * General hub actions (e.g. joining, loading stuff)
         *
         */
        case 'join hub':
          // Validations
          if($MSocial->isMember($hub, $id)) {
            return $this->errorString("already is member");
          }

          $MSocial->joinHub($hub, $id);
          return $success;

        case 'default hub':
          global $MStudent;
          $me = $MStudent->me();
          $me['hubs']['myhub'] = $hub;
          $MStudent->save($me);

          return $success;

        case 'load hub info':
          global $MStudent;
          $me = $MStudent->me();
          $myhub = Isset($me['hubs']['myhub']) ? $me['hubs']['myhub'] : null;

          $entry = $MSocial->get($hub);

          $ret = array(
            'name' => $entry['name'],
            'location' => $entry['location'],
            'banner' => $entry['banner'],
            'ismember' => $MSocial->isMember($hub, $id),
            'myid' => $_SESSION['_id']->{'$id'},
            'myhub' => $myhub == $hub
          );
          return $this->successString($ret);

        case 'load events tab':
          return $this->successString($MSocial->getEvents($hub));

        case 'load members tab':
          $members = $MSocial->getMembers($hub);
          $membersinfo = $MSocial->membersInfo($members);
          return $this->successString($membersinfo);

        case 'load posts tab':
          return $this->successString($MSocial->getHubPosts($hub, 'recent'));

        /* 
         *
         * Posts! Sorting/making/liking/deleting them.
         *
         */
        case 'sort most recent':
          return $this->successString($MSocial->getHubPosts($hub, 'recent'));

        case 'new post':
          if (!$MSocial->isMember($hub, $id))
            return $this->errorString('not member of hub');

          // Validations
          if (!$this->checkIsSet($message, array('content', 'parentid'), $reterr)) {
            return $reterr;
          }
          if (strlen($message['content']) > 2000) {
            return $this->errorString("post too long (exceeds 2000 characters)");
          }

          // If posting within event
          $event = isset($message['event']) ? $message['event'] : null;

          $ret = $MSocial->newPost($id, $hub, $message['content'], $message['parentid'], $event);
          return $this->successString($ret);

        case 'click like':
          if (!$MSocial->isMember($hub, $id))
            return $this->errorString('not member of hub');

          // Validations
          if (!$this->checkIsSet($message, array('postid'), $reterr)) {
            return $reterr;
          }
          $postid = $message['postid'];

          // If posting within event
          if (isset($message['event'])) {
            $event = $message['event'];
            if ($MSocial->getEventIndex($hub, $event) == -1) {
              return $this->errorString("event does not exist");
            }
          } else {
            $event = null;
            if ($MSocial->getPostIndex($hub, $postid) == -1) {
              return $this->errorString("post does not exist");
            }
          }

          $ret = $MSocial->toggleLikePost($hub, $postid, $id, $event);
          return $this->successString($ret, "post $postid $ret");

        case 'delete post':
          // Validations
          if (!$this->checkIsSet($message, array('postid'), $reterr)) {
            return $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            return $this->errorString("post does not exist");
          }
          if (!checkAdmin() && $MSocial->getPostAuthor($hub, $postid) != $id) {
            return $this->errorString("cannot delete someone else's post");
          }

          $ret = $MSocial->deletePost($hub, $postid);
          return $this->successString($ret);

        /* 
         *
         * Events!
         *
         */
        case 'load event info':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          if (($index = $MSocial->getEventIndex($hub, $message['event'])) == -1) {
            return $this->errorString("event does not exist");
          }

          $ret = $MSocial->get($hub);
          $event = $ret['events'][$index];

          // Get host name and pic
          global $MStudent;
          $student = $MStudent->getById($event['creator']);
          $event['hostname'] = $student['name'];
          $event['hostphoto'] = $student['photo'];

          // Check if is creator
          // Also give admins the ability to edit any event
          $event['iscreator'] = ($event['creator'] == $_SESSION['_id'])
              || checkAdmin();

          // Check if is going
          $event['isgoing'] = $MSocial->isGoing($hub, $event['id'], $_SESSION['_id']);

          unset($event['going']);
          unset($event['comments']);

          return $this->successString($event);

        case 'create event': case 'edit event':
          if (!checkAdmin()) {
            if (!$MSocial->isMember($hub, $id))
              return $this->errorString('not member of hub');
            if ($name == 'edit event' and
                !$MSocial->isEventOwner($id, $hub, $message['eventid'])) {
              return $this->errorString("not owner of event");
            }
          }

          // Validations
          if (!$this->checkIsSet($message, 
            array(
              'eventtitle',
              'starttime',
              'endtime',
              'locationname',
              'address',
              'description',
              'banner'
            ), $reterr)) {
            return $reterr;
          }
          if (strlen($message['eventtitle']) > 200) {
            return $this->errorString("event title too long");
          }
          if (strlen($message['description']) > 2000) {
            return $this->errorString("event description too long");
          }
          //TODO Write time validations like below
          // if (!$this->isValidDate($date['starttime'])) {
          //   return $this->errorString("invalid start date: please check date");
          // }
          // if (!$this->isValidDate($date['endtime'])) {
          //   return $this->errorString("invalid end date: please check date");
          // }
          // if(strtotime($data['endtime']) < strtotime($data['starttime'])) {
          //   return $this->errorString("invalid date range: end date should be after start date.");
          // }

          $geocode = geocode($message['address']);

          if ($name == 'create event') {
            return $this->successString($MSocial->createEvent(
              $id,
              $hub,
              $message['eventtitle'],
              $message['starttime'],
              $message['endtime'],
              $message['locationname'],
              $message['address'],
              $geocode,
              $message['description'],
              $message['banner']
            ));
          }
          else if ($name == 'edit event') {
            return $this->successString($MSocial->editEvent(
              $id,
              $hub,
              $message['eventid'],
              $message['eventtitle'],
              $message['starttime'],
              $message['endtime'],
              $message['locationname'],
              $message['address'],
              $geocode,
              $message['description'],
              $message['banner']
            ));
          }

        case 'delete event':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }
          if (!checkAdmin() && $MSocial->getEventCreator($hub, $event) != $id) {
            return $this->errorString("cannot delete someone else's event");
          }

          return $this->successString($MSocial->deleteEvent($hub, $event));

        case 'rsvp event':
          if (!$MSocial->isMember($hub, $id))
            return $this->errorString('not member of hub');

          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }
          if ($MSocial->isGoing($hub, $event, $id)) {
            return $this->errorString("already RSVP'd");
          }

          $MSocial->joinEvent($id, $hub, $event);
          return $this->successString("", "joined event $event");

        case 'leave event':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }
          if (!$MSocial->isGoing($hub, $event, $id)) {
            return $this->errorString("already left event");
          }

          $MSocial->leaveEvent($id, $hub, $event);
          return $this->successString("", "left event $event");

        case 'respond to event':
          if (!$MSocial->isMember($hub, $id))
            return $this->errorString('not member of hub');

          // Validations
          if (!$this->checkIsSet($message, array('event', 'response'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }

          if($message['response'] == 'yes') {
            if ($MSocial->isGoing($hub, $event, $id)) {
              return $this->errorString("already RSVP'd");
            }
            $MSocial->joinEvent($id, $hub, $event);
            return $this->successString("", "joined event $event");
          }
          return $this->successString("", "did not join event $event");

        case 'list going':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }

          $event = $message['event'];

          $attendees = $MSocial->getEventAttendees($hub, $event);
          $attendeesinfo = $MSocial->membersInfo($attendees, 'Going');

          return $this->successString($attendeesinfo);

        case 'load event description':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }

          $event = $message['event'];
          return $this->successString($MSocial->getEventDescription($hub, $event));

        case 'new event comment':
          if (!$MSocial->isMember($hub, $id))
            return $this->errorString('not member of hub');

          // Validations
          if (!$this->checkIsSet($message, array('event', 'content'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }

          $ret = $MSocial->newEventComment($id, $hub, $message['event'], $message['content']);
          return $this->successString($ret);

        case 'delete event comment':
          // Validations
          if (!$this->checkIsSet($message, array('event', 'comment'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }
          $event = $message['event'];
          $comment = $message['comment'];
          if ($MSocial->getEventCommentIndex($hub, $event, $comment) == -1) {
            return $this->errorString("comment does not exist");
          }
          $gotcomment = $MSocial->getEventComment($hub, $event, $comment);
          if (!checkAdmin() && $gotcomment['from'] != $id) {
            return $this->errorString("cannot delete someone else's comment");
          }

          $ret = $MSocial->deleteEventComment($hub, $event, $comment);
          return $this->successString($ret);

        case 'load event comments':
          // NEED TO ADD AN OPTION FOR LOADING POPULAR AS WELL

          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }

          $event = $message['event'];
          return $this->successString($MSocial->getEventComments($hub, $event));

        //TODO Fill in all of the cases below
        case 'sort most popular':
          return $this->successString($MSocial->getHubPosts($hub, 'popular'));

        default:
          return $this->errorString('invalid message name');
      }
    }
    function test() {
      //TODO Write unit tests...
      global $MSocial;

      $_POST['name'] = 'create event';
      $_POST['json'] = '
      {
        "id" : "tony",
        "pass" : "swag",
        "hub" : "5556914f172f559e8ece6c89",
        "eventtitle" : "Rave",
        "starttime" : "5/13/15 9:00:00AM",
        "endtime" : "5/13/15 10:00:00PM",
        "locationname" : "Commons",
        "address" : "1600 Swag Ave.",
        "description" : "Raaaaave",
        "event" : "5556993122e3c76a0e0041a9",
        "content" : "yoyoyoyoyoyoyoyo",
        "comment" : "5556d06122e3c76c0e0041aa",
        "postid" : "55578ae722e3c76c0e0041ab",
        "parentid" : ""
      }';
      echo $this->api() . "<br><br>";
      $entry = $MSocial->get('5556914f172f559e8ece6c89');
      echo var_dump($entry);
    }

    function adminapi() {
      global $MStudent, $MSocial;

      // make sure logged in
      if (!checkAdmin()) {
        return $this->errorString('permission denied');
      }

      $name = $_POST['name'];
      $json = $_POST['json'];

      switch ($name) {
        case 'load students':
          $students = $MStudent->find(array(
            'hubs' => array('$exists' => true))
          );

          $ret = array();
          $counter = 0;
          foreach ($students as $student) {
            $inc = true;

            if (!isset($student['hubs']['geocode']) or 
                is_null($student['hubs']['geocode'])) {
              $city = $student['hubs']['city'];
              $geocode = geocode($city);
              $student['hubs']['geocode'] = $geocode;
              
              if (!is_null($geocode)) $MStudent->save($student);
              else $inc = false;
            }

            if ($inc) $ret[] = $student;
          }

          return $this->successString($ret);
          
        case 'create hub':
          $name = $json['name'];
          $location = geocode($json['location']);
          $banner = $json['banner'];

          if ($location == null)
            return $this->errorString('location invalid');

          $hub = array(
            'name' => $name,
            'location' => $location,
            'banner' => $banner,
            'members' => array(),
            'posts' => array(),
            'events' => array()
          );

          $MSocial->save($hub);

          return $this->successString('hub created');

        case 'load hubs':
          $hubs = $MSocial->getAll();

          $ret = array();
          foreach ($hubs as $hub) {
            $ret[] = $hub;
          }

          return $this->successString($ret);

        /**
         * JSON in form:
         *  {
         *      "students" : [ array of student IDs ]
         *      "hub" : // String containing the ID of the hub e.g. "55566d01172f559e8ece6c88"
         *  }
         */
        case 'add students to hub':
          $students = $json['students'];
          $hub = $json['hub'];
          if (!$hub || $hub == '') {
            return $this->errorString('Please select a hub.');
          }
          foreach ($students as $student) {
            $studententry = $MStudent->getById($student);
            $email = $studententry['email'];
            $hubentry = $MSocial->get($hub);
            $hubname = $hubentry['name'];
            $message = "
              Hey there!
              <br>
              <br>
              After the long wait, your hub is finally ready! Check out your hub <a href=\"https://sublite.net/hubs/hub.php?id=$hub\">here</a>. In need of a new place to go out to eat this weekend? Use your hub to ask questions about your city and meet up with other interns! The possibilities are endless; just keep it civil and respectful.
              <br>
              Best,
              <br>
              <br>
              Sublite Team 
            ";
            sendgmail(array($email), "info@sublite.net", "Welcome to the $hubname social hub on Sublite!", $message);
            $MSocial->joinHub($hub, $student);
          }
          return $this->successString();
      }

      return $this->errorString('invalid message');
    }
  }

  $CSocial = new SocialController();
?>