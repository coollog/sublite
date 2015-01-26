<?php
	$requireLogin = true;
	require_once('header.php');	
	checkAdmin();
	if (isset($_POST['loginas'])) {
		$_SESSION['email'] = $_POST['loginas'];
		header("Location: http://$domain");
		exit();
	}
?>
<pre>
Email,First Name,Last Name
<?php	
	$registered = $E->cEmails->find(array('pass' => array('$exists' => true)), array('_id' => true, 'email' => true, 'name' => true));
	foreach ($registered as $doc) {
		$email = $doc['email'];
		echo "\n$email";
		$name = explode(' ', $doc['name']);
		switch (count($name)) {
			case 0:
				echo ',';
				break;
			case 1:
				echo ','.$name[0];
				break;
			case 2:default:
				echo ','.$name[0].','.$name[1];
				break;
		}
	}
?>
</pre>
<?php
    require_once('footer.php'); 
?>