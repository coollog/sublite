<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class MessageModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct($test=false) {
      parent::__construct(self::DB_TYPE, 'message', $test);
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function add($participants) {
      $data = array(
        'participants' => $participants, 'replies' => array()
      );
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function reply($id, $from, $msg) {
      $entry = $this->get($id);
      array_push($entry['replies'], array(
        'from' => $from, 'msg' => $msg, 'time' => time(), 'read' => false
      ));
      self::$collection->save($entry);
      return $entry;
    }

    function findByParticipant($participant) {
      return self::$collection->find(array(
        'participants' => $participant,
        'replies' => array('$not' => array('$size' => 0))
      ));
    }

    function getLastOf($id) {
      $entry = $this->get($id);
      return array_pop($entry);
    }

    function get($id) {
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function getAll() {
      return self::$collection->find();
    }

    function exists($id) {
      return ($this->get($id) !== NULL);
    }

    protected static $collection;
  }

  GLOBALvarSet('MMessage', new MessageModel());
?>