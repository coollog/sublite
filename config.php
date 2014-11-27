<?php
  $dbuser = getenv('DBUSER');
  $dbpass = getenv('DBPASS');

  $_GLOBALS = array_merge($_GLOBALS, array(
    'dbname' => 'internships',
    'dburi' => "mongo ds051980.mongolab.com:51980/subliteinternships -u $dbuser -p $dbpass";
  ));
?>