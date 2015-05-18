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

      $this->render('socialindex', array('hubs' => true));
    }

    function hub() {
      $this->render('socialhub');
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
      global $CStudent, $MStudent, $MSocial;
      $name = $_POST['name'];
      $json = $_POST['json'];
      $message = $json; // json_decode($json, true);

      // clean data
      foreach ($message as $key => $val) {
        $message[$key] = clean($message[$key]);
      }
      
      $reterr = "";

      // make sure id, pass, and hub are set in $message
      if (!$this->checkIsSet($message, array('hub'), $reterr)) {
        echo $reterr;
      }
      $hub = $message['hub'];

      // make sure password matches
      if (!$CStudent->loggedIn()) {
        echo $this->errorString('you must be logged in');
      }

      // make sure hub exists
      if (!$MSocial->get($hub)) {
        echo $this->errorString("hub doesn't exist");
      }

      // if not trying to join/view hub, make sure is member of hub
      if ($name != 'join hub' && $name != 'load hub info') {
        if (!$MSocial->isMember($hub, $id))
          echo $this->errorString('not member of hub');
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
            echo $this->errorString("already is member");
          }

          $MSocial->joinHub($hub, $id);
          echo $success;

        case 'load hub info':
          $entry = $MSocial->get($hub);
          $ret = array(
            'name' => $entry['name'],
            'location' => $entry['location'],
          );
          echo $this->successString($ret);

        case 'load events tab':
          echo $this->successString($MSocial->getEvents($hub));

        case 'load members tab':
          echo $this->successString($MSocial->getMembers($hub));

        case 'load posts tab':
          echo $this->successString($MSocial->getPosts($hub, '', 'recent'));

        /* 
         *
         * Posts! Sorting/making/liking/deleting them.
         *
         */
        case 'sort most recent':
          echo $this->successString($MSocial->getPosts($hub, '', 'recent'));

        case 'new post':
          // Validations
          if (!$this->checkIsSet($message, array('content', 'parentid'), $reterr)) {
            echo $reterr;
          }
          if (strlen($message['content']) > 2000) {
            echo $this->errorString("post too long (exceeds 2000 characters)");
          }

          $ret = $MSocial->newPost($id, $hub, $message['content'], $message['parentid']);
          echo $this->successString($ret);

        case 'click like':
          // Validations
          if (!$this->checkIsSet($message, array('postid'), $reterr)) {
            echo $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            echo $this->errorString("post does not exist");
          }

          echo $this->successString("", $MSocial->toggleLikePost($hub, $postid, $id));

        case 'delete post':
          // Validations
          if (!$this->checkIsSet($message, array('postid'), $reterr)) {
            echo $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            echo $this->errorString("post does not exist");
          }
          if ($MSocial->getPostAuthor($hub, $postid) != $id) {
            echo $this->errorString("cannot delete someone else's post");
          }

          $ret = $MSocial->deletePost($hub, $postid);
          echo $this->successString($ret);

        /* 
         *
         * Events!
         *
         */
        case 'load event info':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }

          $ret = $MSocial->get($hub);
          $index = $MSocial->getEventIndex($hub, $message['event']);
          unset($ret['events'][$index]['going']);
          unset($ret['events'][$index]['comments']);
          echo $this->successString($ret['events'][$index]);

        case 'create event':
          // Validations
          if (!$this->checkIsSet($message, 
            array(
              'eventtitle',
              'starttime',
              'endtime',
              'locationname',
              'address',
              'description')
            , $reterr)) {
            echo $reterr;
          }
          if (strlen($message['eventtitle']) > 200) {
            echo $this->errorString("event title too long");
          }
          if (strlen($message['description']) > 2000) {
            echo $this->errorString("event description too long");
          }
          //TODO Write time validations like below
          // if (!$this->isValidDate($date['starttime'])) {
          //   echo $this->errorString("invalid start date: please check date");
          // }
          // if (!$this->isValidDate($date['endtime'])) {
          //   echo $this->errorString("invalid end date: please check date");
          // }
          // if(strtotime($data['endtime']) < strtotime($data['starttime'])) {
          //   echo $this->errorString("invalid date range: end date should be after start date.");
          // }

          $geocode = geocode($message['address']);
          echo $this->successString($MSocial->createEvent(
            $id,
            $hub,
            $message['eventtitle'],
            $message['starttime'],
            $message['endtime'],
            $message['locationname'],
            $message['address'],
            $geocode,
            $message['description']
          ));

        case 'delete event':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }
          if ($MSocial->getEventCreator($hub, $event) != $id) {
            echo $this->errorString("cannot delete someone else's event");
          }

          echo $this->successString($MSocial->deleteEvent($hub, $event));

        case 'rsvp event':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }
          if ($MSocial->isGoing($hub, $event, $id)) {
            echo $this->errorString("already RSVP'd");
          }

          $MSocial->joinEvent($id, $hub, $event);
          echo $this->successString("", "joined event $event");

        case 'leave event':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }
          if (!$MSocial->isGoing($hub, $event, $id)) {
            echo $this->errorString("already left event");
          }

          $MSocial->leaveEvent($id, $hub, $event);
          echo $this->successString("", "left event $event");

        case 'respond to event':
          // Validations
          if (!$this->checkIsSet($message, array('event', 'response'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }

          if($message['response'] == 'yes') {
            if ($MSocial->isGoing($hub, $event, $id)) {
              echo $this->errorString("already RSVP'd");
            }
            $MSocial->joinEvent($id, $hub, $event);
            echo $this->successString("", "joined event $event");
          }
          echo $this->successString("", "did not join event $event");

        case 'list going':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }

          $event = $message['event'];
          echo $this->successString($MSocial->getEventAttendees($hub, $event));

        case 'load event description':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }

          $event = $message['event'];
          echo $this->successString($MSocial->getEventDescription($hub, $event));

        case 'new event comment':
          // Validations
          if (!$this->checkIsSet($message, array('event', 'content'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }

          $ret = $MSocial->newEventComment($id, $hub, $message['event'], $message['content']);
          echo $this->successString($ret);

        case 'delete event comment':
          // Validations
          if (!$this->checkIsSet($message, array('event', 'comment'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }
          $event = $message['event'];
          $comment = $message['comment'];
          if ($MSocial->getEventCommentIndex($hub, $event, $comment) == -1) {
            echo $this->errorString("comment does not exist");
          }
          $gotcomment = $MSocial->getEventComment($hub, $event, $comment);
          if ($gotcomment['from'] != $id) {
            echo $this->errorString("cannot delete someone else's comment");
          }

          $ret = $MSocial->deleteEventComment($hub, $event, $comment);
          echo $this->successString($ret);

        case 'load event comments':
          // Validations
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            echo $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            echo $this->errorString("event does not exist");
          }

          $event = $message['event'];
          echo $this->successString($MSocial->getEventComments($hub, $event));

        //TODO Fill in all of the cases below
        case 'sort most popular':
          echo $this->successString($MSocial->getPosts($hub, '', 'popular'));

        default:
          echo $this->errorString('invalid message name');
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
  }

  $CSocial = new SocialController();
?>