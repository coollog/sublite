<?php
  abstract class Model {
    // Database variables
    private $collection;

    function __construct($collectionname) {
      // Setup database
      $m = new MongoClient(); // CHANGE THIS TO CONNECT TO CUSTOM MONGOSERVER
      $db = $m->internships;  // MOVE THIS TO A CONFIG FILE
      $this->collection = $db->$collectionname;
    }
  }
?>