<?php
	$requireLogin = true;
	require_once('header.php');
	
	$id = $_POST['id'];
	
	$q1 = 'No answer.';
	if (isset($_POST['q1'])) {
		$q1 = $_POST['q1'];
	}
	$q1more = htmlentities($_POST['q1more']);
	$q2 = 'No answer.';
	if (isset($_POST['q2'])) {
		$q2 = $_POST['q2'];
	}
	$q3 = $_POST['q3'];
	$q4 = 'No answer.';
	if (isset($_POST['q4'])) {
		$q4 = $_POST['q4'];
	}
	$q4more = htmlentities($_POST['q1more']);
	$q5 = $_POST['q5'];
	$q6 = htmlentities($_POST['q6']);
	
	$message = json_encode($L->get($id));
	$message .= "<br />
		<br /><b>1.) How would you rate your experience with SubLite?</b>
		<br />$q1
		<br /><i>If Improvements Needed, please let us know where to improve:</i>
		<br />$q1more<br />
		<br /><b>2.) Why are you unpublishing or deleting your listing?</b>
		<br />$q2<br />
		<br /><b>3.) How many people have contacted you regarding your place?</b>
		<br />$q3<br />
		<br /><b>4.) Would you like your University Career Services to subscribe to SubLite on your behalf? (So that SubLite remains free for students)</b>
		<br />$q4
		<br /><i>If Yes, please provide a short appeal for your University to subscribe:</i>
		<br />$q4more<br />
		<br /><b>5.) How much would your impression of your University change if they subscribed to the service?</b>
		<br />$q5<br />
		<br /><b>6.) What are some suggestions that you have for us? We would love to hear what you have to say!</b>
		<br />$q6";
	require_once('sendgmail.php');
	sendgmail(array('info@sublite.net', 'SubLite, LLC.'), array('info@sublite.net', 'SubLite, LLC.'), 'SubLite Exit Survey', $message);
	
	require_once('footer.php');
?>