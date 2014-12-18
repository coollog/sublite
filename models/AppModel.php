<?php
  require_once('models/Model.php');

  class AppModel extends Model {
    function __construct() {
      parent::__construct('app');
    }

    function save($data) {
      $this->collection->save($data);
      return $data['_id'];
    }

    function get($id) {
      return $this->collection->findOne(array('_id' => $id));
    }
  }

  $MApp = new AppModel();

?>