<?php
// Bucket Name
$bucket="SubLite";
require_once('../app/pass.php');
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', $GLOBALS['s3access']);
if (!defined('awsSecretKey')) define('awsSecretKey', $GLOBALS['s3secret']);
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>