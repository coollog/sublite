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
          $var = "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"$f not set\"}";
          return false;
        }
      }
      return true;
    }

    function isValidDate($date) {
      //TODO
    }

    function errorString($err) {
      return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"$err\"}";
    }

    function successString($data) {
      return "{\"status\" : \"success\", \"data\" : \"$data\", \"message\" : \"\"}";
    }

    function api() {
      global $MStudent, $MSocial;
      $name = $_POST['name'];
      $json = $_POST['json'];
      $message = json_decode($json, true);
      $reterr = "";
      // make sure id, pass, and hub are set in $message
      if (!$this->checkIsSet($message, array('id', 'pass', 'hub'), $reterr)) {
        return $reterr;
      }
      $id = $message['id'];
      $hub = $message['hub'];
      // make sure password matches
      $email = $MStudent->getEmail($id);
      if (!$MStudent->login($email, $message['pass'])) {
        return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"invalid credentials\"}";
      }

      // make sure hub exists
      if (!$MSocial->get($message['hub'])) {
        return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"hub doesn't exist\"}";
      }

      // if not trying to join/view hub, make sure is member of hub
      if ($name != 'join hub' && $name != 'load hub info') {
        if(!$MSocial->isMember($hub, $id)) return $this->errorString('not member of hub');
      }

      // specific stuff
      $success = "{\"status\" : \"success\", \"data\" : \"\", \"message\" : \"\"}";
      switch ($name) {
        case 'join hub':
          if($MSocial->isMember($hub, $id)) {
            return $this->errorString("already is member");
          }
          $MSocial->add($hub, $id);
          return $this->successString("");
        case 'load hub info':
          $entry = $MSocial->get($hub);
          $ret = array(
            'name' => $entry['name'],
            'location' => $entry['location'],
          );
          return $this->successString(json_encode($ret));
        case 'load events tab':
          return $this->successString(json_encode($MSocial->getEvents($hub)));
        case 'load members tab':
          return $this->successString(json_encode($MSocial->getMembers($hub)));
        case 'load posts tab':
          return $this->successString(json_encode($MSocial->getPosts($hub, '', 'recent')));
        case 'sort most recent':
          return $this->successString(json_encode($MSocial->getPosts($hub, '', 'recent')));
        case 'new post':
          if (!$this->checkIsSet($message, array('content', 'parentid'), $reterr)) {
            return $reterr;
          }
          $ret = $MSocial->newPost($id, $hub, $message['content'], $message['parentid']);
          return $this->successString(json_encode($ret));
        case 'click like':
          if (!$this->checkIsSet($message, array('postid'), $reterr)) {
            return $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            return $this->errorString("post does not exist");
          }
          return $this->successString($MSocial->toggleLikePost($hub, $postid, $id));
        case 'delete post':
          if (!$this->checkIsSet($message, array('postid'), $reterr)) {
            return $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            return $this->errorString("post does not exist");
          }
          if ($MSocial->getPostAuthor($hub, $postid) != $id) {
            return $this->errorString("cannot delete someone else's post");
          }
          $ret = $MSocial->deletePost($hub, $postid);
          return $this->successString(json_encode($ret));
        case 'load event info':
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $ret = $MSocial->get($hub);
          $index = $MSocial->getEventIndex($hub, $message['event']);
          unset($ret['events'][$index]['going']);
          unset($ret['events'][$index]['comments']);
          return $this->successString(json_encode($ret['events'][$index]));
        case 'create event':
          if (!$this->checkIsSet($message, 
            array(
              'eventtitle',
              'starttime',
              'endtime',
              'locationname',
              'address',
              'description')
            , $reterr)) {
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
          return $this->successString(json_encode($MSocial->createEvent(
            $id,
            $hub,
            $message['eventtitle'],
            $message['starttime'],
            $message['endtime'],
            $message['locationname'],
            $message['address'],
            $geocode,
            $message['description']
          )));
        case 'delete meetup':
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return $this->errorString("event does not exist");
          }
          if ($MSocial->getEventCreator($hub, $event) != $id) {
            return $this->errorString("cannot delete someone else's event");
          }
          return $this->successString(json_encode($MSocial->deleteEvent($hub, $event)));
        case 'rsvp event':
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
          return $this->successString("joined event $event");
        case 'leave event':
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
          return $this->successString("left event $event");;
        case 'respond to event':
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
            return $this->successString("joined event $event");
          }
          return $this->successString("did not join event $event");
        case 'list going':
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          return $this->successString(json_encode($MSocial->getEventAttendees($hub, $event)));
        case 'load event description':
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          return $this->successString($MSocial->getEventDescription($hub, $event));
        case 'new event comment':
          if (!$this->checkIsSet($message, array('event', 'content'), $reterr)) {
            return $reterr;
          }
          $ret = $MSocial->newEventComment($id, $hub, $message['event'], $message['content']);
          return $this->successString(json_encode($ret));
        case 'delete event comment':
          if (!$this->checkIsSet($message, array('event', 'comment'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          $comment = $message['comment'];
          if ($MSocial->getEventCommentIndex($hub, $event, $comment) == -1) {
            return $this->errorString("comment does not exist");
          }
          if ($MSocial->getEventComment($hub, $event, $comment)['from'] != $id) {
            return $this->errorString("cannot delete someone else's comment");
          }
          $ret = $MSocial->deleteEventComment($hub, $event, $comment);
          return $this->successString(json_encode($ret));
        case 'load event comments':
          if (!$this->checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          return $this->successString(json_encode($MSocial->getEventComments($hub, $event)));
        case 'go to parent hub':
          return $success;

        //TODO Fill in all of the cases below
        case 'sort most popular':
          return $this->successString(json_encode($MSocial->getPosts($hub, '', 'popular')));
        default:
          return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"invalid message name\"}";          
      }
    }
    function test() {
      global $MSocial;

      $_POST['name'] = 'load event info';
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
        "content" : "yo!!",
        "comment" : "5556d06122e3c76c0e0041aa"
      }';
      echo $this->api() . "<br><br>";
      $entry = $MSocial->get('5556914f172f559e8ece6c89');
      echo var_dump($entry);
    }
  }

  $CSocial = new SocialController();
?>