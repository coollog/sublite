<?php
  abstract class Model {
    // Database variables
    protected $collection;

    function __construct($collectionname) {
      // Setup database
      $m = new MongoClient($GLOBALS['dburi']);
      $db = $m->$GLOBALS['dbname'];
      $this->collection = $db->$collectionname;
    }
  }
?>