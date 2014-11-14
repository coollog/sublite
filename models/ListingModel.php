<?php
  require_once('models/Model.php');

  class ListingModel extends Model {
    function __construct() {
      parent::__construct('listings');
    }

    function create($data) {

    }

    function get($id) {

    }
    
    function edit($id) {

    }
  }

  $MListings = new ListingModel();

?>