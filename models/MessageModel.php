<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class MessageModel extends Model {
    function __construct() {
      parent::__construct('message');
    }

    function add($participants) {
      $data = array('participants' => $participants, 'replies' => array());
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function reply($id, $from, $msg) {
      $entry = $this->get($id);
      array_push($entry['replies'], array(
        'from' => $from, 'msg' => $msg, 'time' => time(), 'read' => false
      ));
      $this->collection->save($entry);
      return $entry;
    }

    function findByParticipant($participant) {
      return $this->collection->find(array(
        'participants' => $participant,
        'replies' => array('$not' => array('$size' => 0))
      ));
    }

    function getLastOf($id) {
      $entry = $this->get($id);
      return array_pop($entry);
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    
    function exists($id) {
      return ($this->get($id) !== NULL);
    }
  }

  $MMessage = new MessageModel();

?>