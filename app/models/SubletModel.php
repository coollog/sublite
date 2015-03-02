<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SubletModel extends Model {
    function __construct() {
      $m = new MongoClient($GLOBALS['dburistudent']);
      $db = $m->$GLOBALS['dbnamestudent'];
      $this->collection = $db->listings;
    }

    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function find($query=array(), $fields=array()) {
      return $this->collection->find($query, $fields);
    }
    function last($n=1) {
      return $this->find(array('publish' => true))->sort(array('_id'=>-1))->limit($n);
    }
    function getAll() {
      return $this->collection->find();
    }
    function getByStudent($id) {
      return $this->collection->find(array('student' => new MongoId($id)));
    }
    
    function delete($id) {
      
    }
    
    function exists($id) {
      return ($this->collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }
  }

  $MSublet = new SubletModel();

?>