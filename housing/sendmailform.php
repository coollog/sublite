<?php
	if (isset($_POST['to'])) {
		/*function sendmail($to, $subject, $message, $from, $bcc = '') {
			mail($to, $subject, "
				<html>
					<head>
						<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
					</head>
					<body>
						$message
					</body>
				</html>
			", "From: $from\r\nBcc: $bcc\r\nReply-To: $from\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset: utf8\r\n");
		}*/
		require_once('sendgmail.php');
		
		$to = $_POST['to'];
		$bcc = $_POST['bcc'];
		$subject = $_POST['subject'];
		$msg = $_POST['msg'];
		$from = $_POST['from'];
		//sendmail($to, $subject, $msg, $from, $bcc);
		sendgmail($to, $from, $subject, $message, null, $bcc);
		echo "Message sent!";
	}
?>
<style>
	input {
		display: block;
	}
	textarea {
		width: 600px;
		height: 600px;
	}
</style>
<form method="post">
	<input name="to" placeholder="To" />
	<input name="bcc" placeholder="Bcc" />
	<input name="subject" placeholder="Subject" />
	<textarea name="msg" placeholder="Message"></textarea>
	<input name="from" placeholder="From" />
	<input type="submit" />
</form>