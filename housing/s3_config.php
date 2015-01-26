<?php
// Bucket Name
$bucket="SubLite";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJRYTLBCWUZ4UWMTA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>