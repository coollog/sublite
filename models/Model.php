<?php
  abstract class Model {
    // Database variables
    private $collection;

    function __construct($collectionname) {
      // Setup database
      $m = new MongoClient($_GLOBALS['dburi']);
      $db = $m->$_GLOBALS['dbname'];
      $this->collection = $db->$collectionname;
    }
  }
?>