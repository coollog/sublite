<?php
	require_once('PHPMailer/PHPMailerAutoload.php');
	function sendgmail($to, $from, $subject, $message, $cc = null, $bcc = null) {
		$mail = new PHPMailer;
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'info@sublite.net';                 // SMTP username
		$mail->Password = $GLOBALS['gmailpass'];              // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS

		if (count($from) > 1) {
			$mail->setFrom($from[0], $from[1]);
			$mail->addReplyTo($from[0], $from[1]);
		} else {
			$mail->setFrom($from);
			$mail->addReplyTo($from);
		}
		if (count($to) > 1) {
			foreach($to as $email) {
				if (count($email) > 1) {
					$mail->addAddress($email[0], $email[1]);
				} else {
					$mail->addAddress($email);
				}
			}
		} else {
			if (is_array($to)) $mail->addAddress($to[0]);
			else $mail->addAddress($to);
		}
		if (!is_null($cc)) {
			$mail->addCC($cc);
		}
		if (!is_null($bcc)) {
			if (is_array($bcc)) {
				foreach ($bcc as $email) {
					$mail->addBCC($email);
				}
			} else
				$mail->addBCC($bcc);
		}
		$mail->WordWrap = 80;
		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = strip_tags($message);

		if (!$mail->send()) {
		   return $mail->ErrorInfo;
		}

		return true;
	}
?>