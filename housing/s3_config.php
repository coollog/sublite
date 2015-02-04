<?php
// Bucket Name
$bucket="SubLite";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAI2G754IIBHBDIFOQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>