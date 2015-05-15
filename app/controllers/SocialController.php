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
      // Check proper formatting
      if(!preg_match('`[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}`', $date)) {
        return false;
      }
      $ar = explode('/', $date);
      // Check if date exists
      if(!checkdate(intval($ar[0]), intval($ar[1]), intval($ar[2])))
        return false;
      // Check if date is in the future
      return strtotime($date) > time();
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
      $message = json_decode($json);
      $reterr = "";
      // make sure id, pass, and hub are set in $message
      if (!checkIsSet($message, array('id', 'pass', 'hub'), $reterr)) {
        return $reterr;
      }
      // make sure password matches
      $email = $MStudent->getEmail($message['id']);
      if (!$MStudent->login($email, $message['pass'])) {
        return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"invalid credentials\"}";
      }
      // make sure hub exists
      if (!$MSocial->get($message['id'])) {
        return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"hub doesn't exist\"}";
      }

      // if not trying to join hub, make sure is member of hub
      if ($name != 'join hub') {
        if(!isMember($hub, $id)) return errorString('not member of hub');
      }

      // specific stuff
      $success = "{\"status\" : \"success\", \"data\" : \"\", \"message\" : \"\"}";
      $id = $message['id'];
      $pass = $message['pass'];
      $hub = $message['hub'];
      switch ($name) {
        case 'join hub':
          add($hub, $id);
          return successString("");
        case 'load posts tab':
          return $success; //TODO supply list of posts
        case 'load events tab':
          return successString(json_encode($MSocial->getEvents($id)));
        case 'load members tab':
          return successString(json_encode($MSocial->getMembers($id)));
        
        case 'new post':
          if (!checkIsSet($message, array('content', 'parentid'), $reterr)) {
            return $reterr;
          }
          $ret = $MSocial->newPost($id, $hub, $message['content'], $message['parentid']);
          return successString(json_encode($ret));

        case 'like post':
          if (!checkIsSet($message, array('postid'), $reterr)) {
            return $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            return errorString("post does not exist");
          }
          $MSocial->likePost($hub, $postid, $id);
          return $success;

        case 'delete post':
          if (!checkIsSet($message, array('postid'), $reterr)) {
            return $reterr;
          }
          $postid = $message['postid'];
          if ($MSocial->getPostIndex($hub, $postid) == -1) {
            return errorString("post does not exist");
          }
          if ($MSocial->getPostAuthor($hub, $postid) != $id) {
            return errorString("cannot delete someone else's post");
          }
          $MSocial->deletePost($hub, $postid);
          return $success;
        case 'create meetup':
          if (!checkIsSet($message, 
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
            return errorString("meetup title too long");
          }
          if (strlen($message['description']) > 2000) {
            return errorString("meetup description too long");
          }
          if (!$this->isValidDate($date['starttime'])) {
            return errorString("invalid start date: please check date");
          }
          if (!$this->isValidDate($date['endtime'])) {
            return errorString("invalid end date: please check date");
          }
          if(strtotime($data['endtime']) < strtotime($data['starttime'])) {
            return errorString("invalid date range: end date should be after start date.");
          }
          $geocode = geocode($message['address']);
          $MSocial->createEvent(
            $id,
            $hub,
            $eventtitle,
            $message['starttime'],
            $message['endtime'],
            $message['locationname'],
            $message['address'],
            $geocode,
            $message['description']
          );
          return $success;
        case 'delete meetup':
          if (!checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return errorString("event does not exist");
          }
          if ($MSocial->getEventCreator($hub, $event) != $id) {
            return errorString("cannot delete someone else's event");
          }
          $MSocial->deleteEvent($hub, $event);
          return $success;
        case 'rsvp meetup':
          if (!checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return errorString("event does not exist");
          }
          $MSocial->joinEvent($id, $hub, $event);
          return $success;
        case 'respond to meetup':
          if (!checkIsSet($message, array('event', 'response'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          if ($MSocial->getEventIndex($hub, $event) == -1) {
            return errorString("event does not exist");
          }
          if($message['response'] == 'yes') {
            $MSocial->joinEvent($id, $hub, $event);
          }
          return $success;
        case 'list going':
          if (!checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          return $successString(json_encode($MSocial->getEventAttendees($hub, $event)));
        case 'load event description':
          if (!checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          return $successString(json_encode($MSocial->getEventDescription($hub, $event)));
        case 'go to parent hub':
          return $success;

        //TODO Fill in all of the cases below
        case 'sort most recent':
          return $success;
        case 'sort most popular':
          return $success;
        case 'load event comments':
          if (!checkIsSet($message, array('event'), $reterr)) {
            return $reterr;
          }
          $event = $message['event'];
          //TODO return $successString(json_encode($MSocial->getEventComments($hub, $event)));
        default:
          return "{\"status\" : \"fail\", \"data\" : \"\", \"message\" : \"invalid message name\"}";          
      }
    }
  }

  $CSocial = new SocialController();
?>