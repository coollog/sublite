<?php
	require_once('header.php'); 
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		if (isset($_POST['pass'])) {
			$orig = $_POST['pass'];
			$pass = md5($_POST['pass']);
			// Check account information
			$doc = $E->getDoc($email);
			if ($pass != $doc['pass']) {
				echo 'Invalid password!<br />(Check your CAPS LOCK)';
			} else {
				$_SESSION['email'] = $email;
				$doc['orig'] = $orig;
				$E->cEmails->save($doc);
			}
		}
	}
	
	require_once('footer.php');
?>