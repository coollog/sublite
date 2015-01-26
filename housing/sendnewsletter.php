<?php
	$requireLogin = true;
	require_once('header.php');	
	checkAdmin();
	require_once('htmlheader.php');
	require_once('formcss.php');
	
	if (isset($_POST['fromemail'])) {
		$subject = $_POST['subject'];
		$msg = nl2br($_POST['msg']);
		$from = array($_POST['fromemail'], $_POST['fromname']);
		
		//require_once('sendmail.php');
		require_once('sendgmail.php');
		$docs = array();
		switch ($_POST['emaillist']) {
			case 'registered':
				$registered = $E->cEmails->find(array('pass' => array('$exists' => true)), array('email' => true, 'confirm' => true));
				foreach ($registered as $doc) {
					if (!isset($doc['unsubscribe']) or !$doc['unsubscribe'])
						array_push($docs, $doc);
				}
				break;
			case 'nonregistered':
				$nonregistered = $E->cEmails->find(array('pass' => array('$exists' => false)), array('email' => true, 'confirm' => true));
				foreach ($nonregistered as $doc) {
					if (!isset($doc['unsubscribe']) or !$doc['unsubscribe'])
						array_push($docs, $doc);
				}
				break;
		}
		$q = $E->cEmails->find(array('email' => 'qingyang.chen@yale.edu'), array('email' => true, 'confirm' => true));
		foreach ($q as $doc) {
			array_push($docs, $doc);
		}
		//$docs = array(array('email' => 'qingyang.chen@yale.edu', 'confirm' => 'hahaha'));
		echo 'Sending to ' . count($docs) . '<br />';
		foreach ($docs as $doc) {
			$msgtmp = str_replace('{#p}', $doc['confirm'], $msg);
			$msgtmp = str_replace('{#email}', $doc['email'], $msgtmp);
			$msgtmp = str_replace('{#confirm}', $doc['confirm'], $msgtmp);
			echo 'Sending to ' . $doc['email'] . '<br />';
			//sendmail($doc['email'], $subject, $msgtmp, $from);
			sendgmail($doc['email'], $from, $subject, $msgtmp);
		}
		echo "Message sent!";
	}
	$date = date('n/j/Y');
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
	To: 
	<input type="radio" name="emaillist" value="registered" checked>Registered Users
	<input type="radio" name="emaillist" value="nonregistered">Non-Confirmed Users
	<input type="radio" name="emaillist" value="test">Testing Purposes
	<input name="fromemail" placeholder="From Email" value='info@sublite.net' />
	<input name="fromname" placeholder="From Name" value='SubLite, LLC.' />
	<input name="subject" placeholder="Subject" value="SubLite Newsletter - <?php echo $date; ?>" />
	<br />Use {#confirm}, {#email}, {#p} for confirmation code, email address, and profile id.
	<textarea name="msg" placeholder="Message"><?php require('emailtemplate.php'); ?></textarea>
	<input type="submit" class="button orange" value="Send Newsletter" />
</form>

<?php
	
	require_once('htmlfooter.php');
    require_once('footer.php'); 
?>