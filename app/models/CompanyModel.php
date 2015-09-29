<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class CompanyModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct() {
      static::$collection =
        parent::__construct(self::DB_TYPE, 'companies');
    }

    function save($data) {
      static::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return static::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function getByName($name) {
      return static::$collection->findOne(array('name' => $name));
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
      return static::$collection->find();
    }
    function find($query) {
      return static::$collection->find($query);
    }

    function delete($id) {

    }

    function exists($id) {
      return ($this->get($id) !== NULL);
    }
  }

  $MCompany = new CompanyModel();

?>