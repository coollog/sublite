<?php
	$requireLogin = true;
	require_once('header.php');
	
	$toemail = $_POST['toemail'];
	$to = array($toemail, $_POST['toname']);
	$subject = htmlentities($_POST['subject']);
	$message = htmlentities($_POST['message']);
	$email = $_SESSION['email'];
	$p = $E->getDoc($email); $name = $p['name'];
	//$from = "\"$name\" <$email>";
	$from = array($email, $name);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo 'Invalid email address!';
	} else {
		if (strlen($subject) == 0) {
			echo 'Subject cannot be blank!';
		} else {
			if (strlen($message) == 0) {
				echo 'Message cannot be blank!';
			} else {
				$message .= '<br /><br />-----------------------<br />Sent from <a href="http://sublite.net">SubLite.net</a>, SubLite LLC.';
				//require_once('sendmail.php');
				require_once('sendgmail.php');
				$message = nl2br($message);
				//if (!sendmail($to, $subject, $message, $from)) {
				if (($error = sendgmail($to, $from, $subject, $message)) !== true) {
					echo 'Message failed to send! Try emailing the owner instead at <a href="mailto: ' . $toemail . '">' . $toemail . '</a>.';
				} else {
					$toemail = htmlentities($toemail);
					//sendmail('info@sublite.net', "Owner Contacted on SubLite: $subject", "To: $to<br />From: $from<br /><br />$message", 'info@sublite.net');
					sendgmail('info@sublite.net', 'info@sublite.net', "Owner Contacted on SubLite: $subject", "To: $toemail<br />From: $email<br /><br />$message");
				}
			}
		}
	}
	
	require_once('footer.php');
?>