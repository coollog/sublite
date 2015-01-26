<?php
	$requireLogin = true;
	require_once('header.php'); 
	
	$listing = $_SESSION['listing'];
	header("Location: http://$domain/edit.php?l=$listing");
	unset($_SESSION['listing']);
	
	require_once('footer.php');
?>