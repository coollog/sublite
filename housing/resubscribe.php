<?php
	require_once('header.php');
	require_once('htmlheader.php');
	
	$p = $_REQUEST['p'];
	$email = $_REQUEST['email'];
	$docs = $E->cEmails->find(array('email' => $email, 'pass' => array('$exists' => true)));
	
	foreach ($docs as $doc) {
		if ($doc['confirm'] == $p) {
			$doc['unsubscribe'] = false;
			$E->cEmails->save($doc);
			echo 'Resubscribed to newsletter!';
		}
	}
	
	require_once('htmlfooter.php'); 
	require_once('footer.php');
?>