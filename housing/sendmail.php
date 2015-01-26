<?php
	function sendmail($to, $subject, $message, $from) {
		return mail($to, $subject, $message, 
			"From: $from\r\nReply-To: $from\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset: utf8\r\n");
	}
?>