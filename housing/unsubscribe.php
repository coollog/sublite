<?php
	require_once('header.php');
	require_once('htmlheader.php');
	
	$p = $_REQUEST['p'];
	$email = $_REQUEST['email'];
	$doc = $E->cEmails->findOne(array('email' => $email, '_id' => new MongoId($p)));
	
	if ($doc) {
		$doc['unsubscribe'] = true;
		$E->cEmails->save($doc);
		echo 'Unsubscribed from newsletter!';
	}
	
	require_once('htmlfooter.php'); 
	require_once('footer.php');
?>