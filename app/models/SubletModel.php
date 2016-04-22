<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SubletModel extends Model {
    const DB_TYPE = parent::DB_STUDENTS;

    function __construct() {
      parent::__construct(self::DB_TYPE, 'listings');

      mongo_ok(self::$collection->createIndex([ 'geoJSON' => '2dsphere' ]));
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
    function getByStudent($id) {
      return self::$collection->find(['student' => new MongoId($id)]);
    }

    function delete($id) {

    }

    function exists($id) {
      return (self::$collection->findOne(array('_id' => new MongoId($id))) !== NULL);
    }

    protected static $collection;
  }

  GLOBALvarSet('MSublet', new SubletModel());
?>
