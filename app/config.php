<?php
  $dbname = 'subliteinternships';
  $dbnamestudent = 'sublite';
  require_once($GLOBALS['dirpre'].'pass.php');

  switch ($env) {
    case 'dev':
      $dbhost = 'localhost:27017';
      $dbstudenthost = 'localhost:27017';
      break;

    default:
      $dbhost = 'ds051980.mongolab.com:51980';
      $dbstudenthost = 'ds047057.mongolab.com:47057';
  }

  $g = array(
    'dbname' => $dbname,
    'dbnamestudent' => $dbnamestudent,

    'dburi' => "mongodb://$dbuser:$dbpass@$dbhost/$dbname",
    'dburistudent' => "mongodb://$dbuserstudent:$dbpassstudent@$dbstudenthost/$dbnamestudent",

    'domain' => "sublite.net/employers",
    'gmailpass' => $gmailpass,
    's3access' => $s3access,
    's3secret' => $s3secret
  );

  $GLOBALS = array_merge($GLOBALS, $g);


  // Config Stripe.

  require_once('stripe/init.php');

  $stripe = array(
    "secret_key"      => "sk_test_wNJGSrwbxKLnmSE6UArhnSqb",
    "publishable_key" => "pk_test_ESBSVzQl2DxjcS7QcGPuVjVw"
  );

  \Stripe\Stripe::setApiKey($stripe['secret_key']);
?>