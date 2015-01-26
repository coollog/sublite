<?php
	require('header.php');
	
	if (isset($_POST['emails'])) {
		// Add emails
		$emailarr = explode("\r\n", $_POST['emails']);
		$emaildoc = array();
		$emailinvalid = '';
		foreach ($emailarr as $email) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emaildoc[] = array('email' => $email);
			} else {
				$emailinvalid .= "$email\r\n";
			}
		}
		if (count($emaildoc) > 0) {
			$E->addBatch($emaildoc);
			echo "Emails inserted!";
		} else {
			echo "No emails inserted!";
		}
		if (strlen($emailinvalid) > 0) {
			echo "<br />Invalid emails: <pre>$emailinvalid</pre>";
		}
	}
	if (isset($_POST['remails'])) {
		// Remove emails
		$emailarr = explode("\r\n", $_POST['remails']);
		$emaildoc = array();
		$emailinvalid = '';
		foreach ($emailarr as $email) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emaildoc[] = array('email' => $email);
			} else {
				$emailinvalid .= "$email\r\n";
			}
		}
		if (count($emaildoc) > 0) {
			$E->removeBatch($emaildoc);
			echo "Emails removed!";
		} else {
			echo "No emails removed!";
		}
		if (strlen($emailinvalid) > 0) {
			echo "<br />Invalid emails: <pre>$emailinvalid</pre>";
		}
	}
	
	require_once('footer.php');
?>