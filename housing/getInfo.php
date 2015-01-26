<?php
	$requireLogin = true;
	require_once('header.php');	
	checkAdmin();
	require_once('htmlheader.php');
	require_once('formcss.php');
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		$p = $E->getDoc($email);
		$confirm = $p['confirm'];
?>
		<div>Confirmation Link: <?php echo "<a href='http://sublite.net/confirm.php?id=$confirm&email=$email'>http://sublite.net/confirm.php?id=$confirm&email=$email</a>"; ?></div>
<?php
	}
?>

<form method="post">
	<input type="text" name="email" placeholder="Email" />
	<input type="submit" value="Get Info" />
</form>

<?php
	
	require_once('htmlfooter.php');
    require_once('footer.php'); 
?>