<?php
	require_once('header.php');
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "-2";
		} else {
			if ($S->verify($email))
				echo "0";
			else
				echo "-1";
		}
	}
	
	require_once('footer.php');
?>