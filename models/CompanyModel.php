<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class CompanyModel extends Model {
    function __construct() {
      parent::__construct('companies');
    }

    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function getByName($name) {
      return $this->collection->findOne(array('name' => $name));
    }
    function getName($id) {
      $entry = $this->get($id);
      return $entry['name'];
    }
    function getIndustry($id) {
      $entry = $this->get($id);
      return $entry['industry'];
    }
    function find($query) {
      return $this->collection->find($query);
    }

    function delete($id) {
      
    }
    
    function exists($id) {
      return ($this->get($id) !== NULL);
    }
  }

  $MCompany = new CompanyModel();

?>