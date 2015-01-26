<?php
	$requireLogin = true;
	require_once('header.php'); 
	
	$afterLogin = $_SESSION['afterLogin'];
	header("Location: http://$domain/$afterLogin");
	unset($_SESSION['afterLogin']);
	
	require_once('footer.php');
?>