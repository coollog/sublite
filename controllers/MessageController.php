<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class MessageController extends Controller {

    function newData($data) {
      $from = clean($data['from']);
      $to = clean($data['to']);
      return array(
        'participants' => array($from, $to);
      );
    }
    function new() {
      global $CJob; $CJob->requireLogin();

      global $params, $MMessage, $MRecruiter, $MStudent;

      // Params to vars
      extract($data = $this->newData($_REQUEST));

      // Validations
      $this->startValidations();
      $this->validate($MRecruiter->exists($participants[0]) or $MStudent->exists($participants[0]), 
        $err, 'invalid sender');
      $this->validate($MRecruiter->exists($participants[1]) or $MStudent->exists($participants[1]), 
        $err, 'invalid receiver');

      // Code
      if ($this->isValid()) {
        $id = $MMessage->new($participants);
        $this->redirect('messages', array('id' => $id));
        return;
      }
      
      $this->error($err);
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
      
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and 
                      MongoId::isValid($id = $_GET['id']) and 
                      ($entry = $MMessage->get($id)) !== NULL, 
        $err, 'unknown message');
      if ($this->isValid())
        $this->validate(in_array($myid = $_SESSION['_id']->{'$id'}, $entry['participants']),
          $err, 'permission denied');

      // Code
      if ($this->isValid()) {
        function viewData($entry) {
          return array(
            'messages' => $MMessage->findByParticipant($myid),
            'current' => $entry
          );
        }

        if (!isset($_POST['reply'])) {
          $this->render('messages', viewData($entry)); return;
        }

        extract($data = $this->data($params));
        // Validations
        $this->validate(strlen($msg) > 0, $err, 'message empty');

        if ($this->isValid()) {
          $MMessage->reply($entry['_id']->{'$id'}, $myid, $msg);

          $this->render('messages', viewData($entry));
          return;
        }
      }
      
      $this->error($err);
    }
  }

  $CMessage = new MessageController();

?>