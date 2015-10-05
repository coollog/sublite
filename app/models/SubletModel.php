<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SubletModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct() {
      self::$collection = parent::__construct(self::DB_TYPE, 'listings');
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function find($query=array(), $fields=array()) {
      return self::$collection->find($query, $fields);
    }
    function last($n=1) {
      return $this->find(array('publish' => true))->sort(array('_id'=>-1))->limit($n);
    }
    function getAll() {
      return self::$collection->find();
    }
    function getByStudent($id) {
      return self::$collection->find(array('student' => new MongoId($id)));
    }

    function delete($id) {

    }

    function exists($id) {
      return (self::$collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }

    private static $collection;
  }

  GLOBALvarSet('MSublet', new SubletModel());
?>