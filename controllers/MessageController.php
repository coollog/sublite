<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class MessageController extends Controller {

    function newData($data) {
      $from = clean($data['from']);
      $to = clean($data['to']);
      return array(
        'participants' => array($from, $to)
      );
    }
    function add() {
      global $CJob; $CJob->requireLogin();

      global $params, $MMessage, $MRecruiter, $MStudent;

      // Params to vars
      extract($data = $this->newData($_REQUEST));

      // Validations
      $this->startValidations();
      $this->validate($MRecruiter->IDexists($participants[0]) or $MStudent->exists($participants[0]), 
        $err, 'invalid sender');
      $this->validate($MRecruiter->IDexists($participants[1]) or $MStudent->exists($participants[1]), 
        $err, 'invalid receiver');

      // Code
      if ($this->isValid()) {
        $id = $MMessage->add($participants);
        $this->redirect('messages', array('id' => $id));
        return;
      }
      
      $this->error($err);
      $this->render('notice');
    }

    function data($data) {
      $msg = clean($data['msg']);
      return array(
        'msg' => $msg
      );
    }
    function reply() {
      global $CJob; $CJob->requireLogin();
      
      global $params, $MMessage;
      // Params to vars

      function viewData($entry=NULL) {
        global $MMessage;
        $messages = array_reverse(iterator_to_array($MMessage->findByParticipant($_SESSION['_id']->{'$id'})));

        function getName($p) {
          global $MStudent, $MRecruiter;
          if ($MStudent->exists($p)) {
            $name = $MStudent->getName($p);
          } else if ($MRecruiter->IDexists($p)) {
            $name = $MRecruiter->getName($p);
          } else {
            $name = 'Nonexistent';
          }
          return $name;
        }
        function setFromNamePic(&$reply, $from) {
          global $MStudent, $MRecruiter;
          $reply['fromname'] = getName($from);
          if ($MStudent->exists($from)) {
            $reply['fromname'] = $MStudent->getName($from);
            $reply['frompic'] = $MStudent->getPic($from);
          } else if ($MRecruiter->IDexists($from)) {
            $reply['fromname'] = $MRecruiter->getName($from);
            $reply['frompic'] = $MRecruiter->getPic($from);
          } else {
            $reply['fromname'] = 'Nonexistent';
            $reply['frompic'] = 'Nonexistent';
          }
          if ($reply['frompic'] == 'assets/gfx/defaultpic.png')
            $reply['frompic'] = $GLOBALS['dirpre'].$reply['frompic'];
        }

        $replies = array();
        $unread = 0;
        foreach ($messages as $m) {
          $reply = array_pop($m['replies']);
          $reply['_id'] = $m['_id'];

          $from = $reply['from'];
          if (!$reply['read']) {
            $reply['read'] = (strcmp($from, $_SESSION['_id']) == 0);
          }
          if (!$reply['read']) $unread ++;

          setFromNamePic($reply, $from);
          
          if (strcmp($m['_id'], $entry['_id']) == 0) $reply['current'] = true;
          else $reply['current'] = false;

          $reply['time'] = timeAgo($reply['time']);

          if (strlen($reply['msg']) > 100) {
            $reply['msg'] = substr($reply['msg'], 0, 97) . '...';
          }

          array_push($replies, $reply);
        }

        if (!is_null($entry)) {
          $currentreplies = $entry['replies'];
          $current = array();
          foreach ($currentreplies as $m) {
            setFromNamePic($m, $m['from']);
            $m['time'] = timeAgo($m['time']);
            array_push($current, $m);
          }

          $to = 'Message To: ' . getName($entry['participants'][0]);
          foreach ($entry['participants'] as $p) {
            if (strcmp($p, $_SESSION['_id']) != 0) {
              $to = 'Message To: ' . getName($p);
            }
          }
        } else {
          $current = null;
          $to = '';
        }

        return array(
          'messages' => $replies,
          'current' => $current,
          'unread' => $unread,
          'to' => $to
        );
      }
      
      if (!isset($_GET['id'])) {
        $this->render('messages', viewData()); return;
      }

      // Validations
      $this->startValidations();
      $this->validate(MongoId::isValid($id = $_GET['id']) and 
                      ($entry = $MMessage->get($id)) !== NULL, 
        $err, 'unknown message');
      if ($this->isValid())
        $this->validate(in_array($myid = $_SESSION['_id']->{'$id'}, $entry['participants']),
          $err, 'permission denied');

      // Code
      if ($this->isValid()) {
        // Set replies to read
        for ($i = 0; $i < count($entry['replies']); $i ++) {
          if (strcmp($entry['replies'][$i]['from'], $_SESSION['_id']) != 0)
            $entry['replies'][$i]['read'] = true;
        }
        $MMessage->save($entry);

        if (!isset($_POST['reply'])) {
          $this->render('messages', viewData($entry)); return;
        }

        extract($data = $this->data($params));
        // Validations
        $this->validate(strlen($msg) > 0, $err, 'message empty');

        if ($this->isValid()) {
          $entry = $MMessage->reply($entry['_id']->{'$id'}, $myid, $msg);

          $this->render('messages', viewData($entry));
          return;
        }

      }
      
      $this->error($err);
      $this->render('notice');
    }
  }

  $CMessage = new MessageController();

?>