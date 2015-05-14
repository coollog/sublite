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
          $MSocial->deletePost($hub, $postid, $id);
          return $success;

        //TODO Fill in all of the cases below
        case 'sort most recent':
          return $success;
        case 'sort most popular':
          return $success;
        case 'create meetup':
          return $success;
        case 'delete meetup':
          return $success;
        case 'rsvp meetup':
          return $success;
        case 'respond to meetup':
          return $success;
        case 'list going':
          return $success;
        case 'load event description':
          return $success;
        case 'load event comments':
          return $success;
        case 'go to parent hub':
          return $success;
        default:
          return 'fail\n{"error":"invalid message name"}';          
      }
    }
  }

  $CSocial = new SocialController();
?>