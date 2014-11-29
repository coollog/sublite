<?php
  require_once('models/Model.php');

  class RecruiterModel extends Model {
    function __construct() {
      parent::__construct('recruiters');
    }

    function save($data) {
      $data['msgs'] = array();
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    function login($email, $pass) {
      if (($entry = $this->get($email)) === NULL) return false;
      return hash_equals($entry['pass'], crypt($pass, $entry['pass']));
    }

    function get($email) {
      return $this->collection->findOne(array('email' => $email));
    }
    function getByID($id) {
      return $this->collection->findOne(array('_id' => new MongoID($id)));
    }
    
    function exists($email) {
      return ($this->get($email) !== NULL);
    }

    function data($entry) {
      $email = $entry['email'];
      $firstname = $entry['firstname'];
      $lastname = $entry['lastname'];
      $company = $entry['company'];
      $title = $entry['title'];
      $phone = $entry['phone'];
      $data = array(
        'email' => $email, 'firstname' => $firstname, 
        'lastname' => $lastname, 'company' => $company, 'title' => $title,
        'phone' => $phone
      );
      return $data;
    }
  }

  $MRecruiter = new RecruiterModel();

?>