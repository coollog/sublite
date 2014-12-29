<?php
  // Bucket Name
  $bucket="SubLite";
  require_once($dirpre.'includes/S3/S3.php');
  			
  //AWS access info
  if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIFY3HR67WRY773QQ');
  if (!defined('awsSecretKey')) define('awsSecretKey', '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');
  			
  //instantiate the class
  $s3 = new S3(awsAccessKey, awsSecretKey);

  $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>