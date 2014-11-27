<?php
  require_once('models/Model.php');

  class RecruiterModel extends Model {
    function __construct() {
      parent::__construct('recruiters');
    }

    function save($email, $pass, $firstname, $lastname, $company, 
                  $title, $phone) {
      $data = array(
        'email' => $email,
        'pass' => $pass,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'company' => $company,
        'title' => $title,
        'phone' => $phone,
        'msgs' => array()
      );
      $this->collection->save($data);
      return $data['_id'];
    }

    function login($email, $pass) {
      if (($entry = $this->get($email)) == NULL) return false;
      return ($entry['pass'] == $pass);
    }

    function get($email) {
      return $this->collection->findOne(array('email' => $email);
    }
    
    function exists($email) {
      return ($this->get($email) == NULL);
    }
  }

  $MRecruiter = new RecruiterModel();

?>