<?php
  abstract class Model {
    // Database variables
    protected $collection;

    function __construct($collectionname) {
      try {
        // Setup database
        $m = new MongoClient($GLOBALS['dburi']);
      } catch(MongoConnectionException $e) {
        trigger_error('Mongodb not available');
      }
      $db = $m->$GLOBALS['dbname'];
      $this->collection = $db->$collectionname;
    }
  }
?>