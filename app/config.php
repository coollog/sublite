<?php
  $dbuser = getenv('DBUSER');
  $dbpass = getenv('DBPASS');
  $dbname = 'subliteinternships';
  $dbnamestudent = 'sublite';
  $dbnamestudenttest = 'sublitetest';
  require_once($GLOBALS['dirpre'].'pass.php');

  $GLOBALS = array_merge($GLOBALS, array(
    'dbname' => $dbname,
    'dburi' => "mongodb://$dbuser:$dbpass@ds051980.mongolab.com:51980/$dbname",
    'dbnamestudent' => $dbnamestudent,
    'dburistudent' => "mongodb://$dbuserstudent:$dbpassstudent@ds047057.mongolab.com:47057/$dbnamestudent",
    'dbnamestudenttest' => $dbnamestudenttest,
    'dburistudenttest' => "mongodb://$dbuserstudent:$dbpassstudent@ds063170.mongolab.com:63170/$dbnamestudenttest",
    'domain' => "sublite.net/employers",
    'gmailpass' => $gmailpass,
    's3access' => $s3access,
    's3secret' => $s3secret
  ));
?>