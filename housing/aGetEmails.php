<?php
	require('header.php');
	
	// Get existing Emails
	$emails = $E->getArr();
	$emailtxt = '';
	foreach($emails as $email) {
		$emailtxt .= "$email\n";
	}
	echo (strlen($emailtxt) > 0) ? $emailtxt : 'No emails in whitelist!';
	
	require_once('footer.php');
?>