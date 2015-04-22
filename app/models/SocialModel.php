<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SocialModel extends Model {
    function __construct() {
      $m = new MongoClient($GLOBALS['dburistudent']);
      $db = $m->$GLOBALS['dbnamestudent'];
      //$this->collection = $db->!!TODO!!;
    }
  }

  $MSocial = new SocialModel();
?>