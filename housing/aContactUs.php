<?php
	require_once('header.php');
	
	$to = 'info@sublite.net';
	$subject = htmlentities($_POST['subject']);
	$message = htmlentities($_POST['message']);
	$from = htmlentities($_POST['from']);
	$name = htmlentities($_POST['name']);
	if (strlen($name) == 0) {
		echo 'Name cannot be blank!';
	} else {
		if (strlen($subject) == 0) {
			echo 'Subject cannot be blank!';
		} else {
			if (strlen($message) == 0) {
				echo 'Message cannot be blank!';
			} else {
				$message .= '<br /><br />---------------------------<br/>MESSAGE SENT THROUGH SUBLITE.NET';
				if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
					echo 'You must use a valid email address!';
				} else {
					//require_once('sendmail.php');
					require_once('sendgmail.php');
					$message = nl2br($message);
					//if (!sendmail('info@sublite.net', $subject, $message, "\"$name\" <$from>")) {
					if (sendgmail('info@sublite.net', array($from, $name), $subject, $message) !== true) {
						echo 'Message failed to send! Try emailing us instead at <a href="mailto: info@sublite.net">info@sublite.net</a>.';
					}
				}
			}
		}
	}
	
	require_once('footer.php');
?>