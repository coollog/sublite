<?php
  abstract class Model {
    // Database variables
    private $collection;

    function __construct($collectionname) {var_dump($GLOBALS);
      // Setup database
      $m = new MongoClient($GLOBALS['dburi']);
      $db = $m->$GLOBALS['dbname'];
      $this->collection = $db->$collectionname;
    }
  }
?>