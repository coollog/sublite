<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SocialModel extends Model {
    function __construct() {
      parent::__construct('hubs');
    }
    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }
    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function isMember($hub, $student) {
      $entry = $this->get($hub);
      foreach ($entry['members'] as $key => & $sub_array) {
        if($sub_array['id'] == $student) return true;
      }
      return false;
    }
    function add($hub, $student) {
      $entry = $this->get($hub);
      $entry['members'][] = array('time' => time(), 'id' => $student);
      $this->save($entry, false);
      return $entry['members'];
    }
  }

  $MSocial = new SocialModel();
?>