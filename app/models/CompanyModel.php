<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class CompanyModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct() {
      parent::__construct(self::DB_TYPE, 'companies');
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function getByName($name) {
      return self::$collection->findOne(array('name' => $name));
    }
    function getName($id) {
      $entry = $this->get($id);
      return $entry['name'];
    }
    function getIndustry($id) {
      $entry = $this->get($id);
      return $entry['industry'];
    }
    function getAll() {
      return self::$collection->find();
    }
    function find($query) {
      return self::$collection->find($query);
    }

    function delete($id) {

    }

    function exists($id) {
      return ($this->get($id) !== NULL);
    }

    protected static $collection;
  }

  GLOBALvarSet('MCompany', new CompanyModel());
?>