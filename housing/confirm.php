<?php
	require_once('header.php'); 
	require_once('htmlheader.php');
	
	if (isset($_REQUEST['id'])) {
		$confirm = $_REQUEST['id'];
		if (isset($_REQUEST['email'])) {
			$email = $_REQUEST['email'];
			
			$doc = $E->getDoc($email);
			if (isset($doc['confirm']) and $doc['confirm'] == $confirm and !isset($doc['pass'])) {
				require('fRegister.php');
			} else {
				echo 'Invalid confirmation link!';
			}
		}
	}
	
	require_once('htmlfooter.php'); 
	require_once('footer.php');
?>