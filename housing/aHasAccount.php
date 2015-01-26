<?php
	require('header.php');
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		if ($E->hasAccount($email)) {
			echo '0';
		} else {
			echo '-1';
		}
	}
	
	require_once('footer.php');
?>