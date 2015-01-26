<?php
	require_once('header.php');
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		
		if ($S->verify($email) and filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// Add email
			$emaildoc = array(array('email' => $email));
			$E->addBatch($emaildoc);
		
			$id = md5(uniqid($email, true));
			$link = "http://$domain/confirm.php?id=$id&email=$email";
			$doc = $E->getDoc($email);
			$doc['confirm'] = $id;
			$E->cEmails->save($doc);
			
			//require_once('sendmail.php');
			require_once('sendgmail.php');
			/*$message = "
				<h1 style=\"padding: 0.5em 0; margin: 1em 0; background: #f78d1d; color: #fff; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4); text-align: center;\">Welcome to SubLite!</h1>
				<br /><br />
				We care about facilitating verified summer sublets from students to students. Whether you are looking for a summer sublet or have a vacant space to sublet, we want to ensure a safe and secure experience for you. That&rsquo;s why we need you to click on the link below to verify your email address. 
				<br /><br />
				<a href='$link'>$link</a>
				<br /><br /><br />
				<i>Thanks again and welcome aboard!<br />
				Team SubLite</i>";*/
			$message = "
				<h1 style=\"padding: 0.5em 0; margin: 1em 0; background: #f78d1d; color: #fff; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4); text-align: center;\">Welcome to SubLite!</h1>
				Hi there!
				<br /><br />
				My name is Yuanling and I&rsquo;m a co-founder of SubLite. We care about facilitating verified summer sublets from students to students. Whether you are looking for a summer sublet or have a vacant space to sublet, we want to ensure a safe and secure experience for you. That&rsquo;s why we need you to click on the link below to verify your email address.
				<br /><br />
				<a href='$link'>$link</a>
				<br /><br /><br />
				<i>Thanks again and welcome aboard!<br />
				Team SubLite</i>";
			//sendmail($email, 'SubLite Email Confirmation', $message, '"SubLite, LLC." <info@sublite.net>');
			//sendmail($email, 'SubLite Email Confirmation', $message, '"Yuanling Yuan - SubLite, LLC." <yuanling.yuan@yale.edu>');
			
			if (($error = sendgmail($email, array("info@sublite.net", "Yuanling Yuan - SubLite, LLC."), 'SubLite Email Confirmation', $message)) !== true) {
				sendgmail('info@sublite.net', 'info@sublite.net', 'Email Confirmation Failed to Send', "Email address: $email<br />Reason: $error");
			}			
			
			echo $email;
			//echo $message;
		}
	}
	
	require_once('footer.php');
?>