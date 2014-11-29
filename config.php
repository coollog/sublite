<?php
  $dbuser = getenv('DBUSER');
  $dbpass = getenv('DBPASS');
  require_once('pass.php');

  $GLOBALS = array_merge($GLOBALS, array(
    'dbname' => 'internships',
    'dburi' => "mongo ds051980.mongolab.com:51980/subliteinternships -u $dbuser -p $dbpass"
  ));
?>