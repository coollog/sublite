<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class MessageController extends Controller {

    function newData($data) {
      $from = clean($data['from']);
      $to = clean($data['to']);
      $msg = isset($data['msg']) ? clean($data['msg']) : '';
      return array(
        'participants' => array($from, $to),
        'msg' => $msg
      );
    }
    function add() {
      global $CJob; $CJob->requireLogin();

      global $params;

      // Params to vars
      extract($data = $this->newData($_REQUEST));
      $pid1 = new MongoId($participants[0]);
      $pid2 = new MongoId($participants[1]);

      // Validations
      $this->startValidations();
      $this->validate(RecruiterModel::exists($pid1) or
                      StudentModel::exists($pid1),
        $err, 'invalid sender');
      $this->validate(RecruiterModel::exists($pid2) or
                      StudentModel::exists($pid2),
        $err, 'invalid receiver');

      // Code
      if ($this->isValid()) {
        $id = MessageModel::add($participants);
        $this->redirect('messages', array('id' => $id, 'msg' => $msg));
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

    // Some helper functions
    function getName(MongoId $pid) {
      if (StudentModel::exists($pid)) {
        $name = StudentModel::getName($pid);
      } else if (RecruiterModel::exists($pid)) {
        $name = RecruiterModel::getName($pid);
      } else {
        $name = 'Nonexistent';
      }
      return $name;
    }
    function getEmail(MongoId $pid) {
      if (StudentModel::exists($pid)) {
        $name = StudentModel::getEmail($pid);
      } else if (RecruiterModel::exists($pid)) {
        $name = RecruiterModel::getEmail($pid);
      } else {
        $name = 'Nonexistent';
      }
      return $name;
    }
    function getType(MongoId $pid) {
      if (StudentModel::exists($pid)) {
        return 'student';
      } else if (RecruiterModel::exists($pid)) {
        return 'recruiter';
      } else {
        return 'none';
      }
    }
    function setFromNamePic(&$reply, MongoId $from) {
      $reply['fromname'] = $this->getName($from);
      if (StudentModel::exists($from)) {
        $photo = StudentModel::getPhoto($from);
        if ($photo == '' or $photo == 'defaultpic.png' or $photo == 'noprofilepic.png')
          $photo = $GLOBALS['dirpreFromRoute'].'assets/gfx/defaultpic.png';
        $reply['frompic'] = $photo;
      } else if (RecruiterModel::exists($from)) {
        $reply['frompic'] = RecruiterModel::getPhoto($from);
      } else {
        $reply['frompic'] = 'Nonexistent';
      }
      if ($reply['frompic'] == 'assets/gfx/defaultpic.png')
        $reply['frompic'] = $GLOBALS['dirpreFromRoute'].$reply['frompic'];
    }

    function reply() {
      global $CJob; $CJob->requireLogin();

      global $params;
      // Params to vars

      // Processes message data
      function viewData($c, $entry=NULL) {
        $messages = array_reverse(
          MessageModel::findByParticipant($_SESSION['_id']->{'$id'})
        );

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

          $c->setFromNamePic($reply, new MongoId($from));

          if (strcmp($m['_id'], $entry['_id']) == 0) $reply['current'] = true;
          else $reply['current'] = false;

          $reply['time'] = timeAgo($reply['time']);

          if (strlen($reply['msg']) > 100) {
            $reply['msg'] = substr($reply['msg'], 0, 97) . '...';
          }

          array_push($replies, $reply);
        }

        // Handle current message
        if (!is_null($entry)) {
          $currentreplies = $entry['replies'];
          $current = array();
          foreach ($currentreplies as $m) {
            $c->setFromNamePic($m, new MongoId($m['from']));
            $m['time'] = timeAgo($m['time']);
            array_push($current, $m);
          }

          $to = 'Message To: ' . $c->getName(new MongoId($entry['participants'][0]));
          foreach ($entry['participants'] as $p) {
            if (strcmp($p, $_SESSION['_id']) != 0) {
              $to = 'Message To: ' . $c->getName($p);
            }
          }
          $currentid = $entry['_id'];
        } else {
          $current = null;
          $currentid = null;
          $to = '';
        }

        $data = array(
          'messages' => $replies,
          'current' => $current,
          'currentid' => $currentid,
          'unread' => $unread,
          'to' => $to
        );
        if (isset($_GET['msg'])) $data['msg'] = $_GET['msg'];
        return $data;
      }

      if (!isset($_GET['id'])) {
        $this->render('messaging/messages', viewData($this)); return;
      }

      /* ACTUALLY SEND MESSAGES */

      // Validations
      $this->startValidations();
      $this->validate(MongoId::isValid($id = new MongoId($_GET['id'])) and
                      ($entry = MessageModel::getById($id)) !== NULL,
        $err, 'unknown message');
      if ($this->isValid())
        $this->validate(in_array($myid = $_SESSION['_id']->{'$id'}, $entry['participants']),
          $err, 'permission denied');

      if ($this->isValid()) {
        // Set replies to read
        $repliesn = count($entry['replies']);
        for ($i = 0; $i < $repliesn; $i ++) {
          if (strcmp($entry['replies'][$i]['from'], $_SESSION['_id']) != 0)
            $entry['replies'][$i]['read'] = true;
        }
        MessageModel::save($entry);

        if (!isset($_POST['reply'])) {
          $this->render('messaging/messages', viewData($this, $entry)); return;
        }

        extract($data = $this->data($params));
        // Validations
        $this->validate(strlen($msg) > 0, $err, 'message empty');
        if ($repliesn > 0) {
          $replylast = $entry['replies'][$repliesn-1];
          $this->validate($msg != $replylast['msg'] or (time() - $replylast['time']) > 10, $err, 'message sent');
        }

        if ($this->isValid()) {
          // Send the message
          $msgid = $entry['_id']->{'$id'};
          $from = $myid;
          $fromname = $this->getName(new MongoId($from));
          $tos = array_remove($entry['participants'], $from);
          $entry = MessageModel::reply(new MongoId($msgid), $from, $msg);

          $emails = array();
          foreach ($tos as $to) {
            $emails[] = $this->getEmail(new MongoId($to));
          }

          // Notify recipients by email
          $link = "http://sublite.net/housing/messages.php?id=$msgid";
          $message = "
            $fromname has sent you a message on SubLite:
            <br /><br />
            View the message on SubLite: <a href='$link'>$link</a>
            <br />
            ---
            <br /><br />
            $msg
            <br /><br />
            ---
            <br />
            Reply to this message <a href='$link'>on SubLite</a>. DO NOT REPLY DIRECTLY TO THIS EMAIL.
          ";
          sendgmail($emails, array("info@sublite.net", "SubLite, LLC."), "Message from $fromname | SubLite", $message);

          // Notify us of the message
          $toemails = implode(', ', $emails);
          $fromemail = $this->getEmail(new MongoId($from));
          $prevmsgs = '';
          $replies = array_reverse($entry['replies']);
          foreach ($replies as $reply) {
            $pfromemail = $this->getEmail(new MongoId($reply['from']));
            $pmsg = $reply['msg'];
            $prevmsgs .= "<b>$pfromemail</b>: <br />$pmsg<br />";
          }
          $message = "
            <b>$fromemail</b> has sent a message to <b>$toemails</b>:
            <br /><br />
            $msg
            <br /><br />
            msgid: $msgid
            <br /><br />
            The thread:
            <br /><br />
            $prevmsgs
          ";
          sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'Message sent on SubLite!', $message);

          $this->render('messaging/messages', viewData($this, $entry));
          return;
        }
        $this->render('messaging/messages', viewData($this, $entry)); return;
      }

      $this->error($err);
      $this->render('notice');
    }
  }

  GLOBALvarSet('CMessage', new MessageController());
?>