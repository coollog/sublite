<?php
	$requireLogin = true;
	require_once('header.php');
	
	$email = $_SESSION['email'];
	$name = htmlentities($_POST['name']);
	$pic = htmlentities($_POST['pic']);
	$class = (int)$_POST['class'];
	$school = htmlentities($_POST['school']);
	$bio = htmlentities($_POST['bio']);
	if (strlen($name) == 0) {
		echo 'Name cannot be empty!';
	} else {
		if ($class < 1900 || $class > 2100) {
			echo 'Invalid class year!';
		} else {
			// Save account information
			$doc = $E->getDoc($email);
			$doc['name'] = $name;
			if ($pic == 'nopic.png') $pic = 'noprofilepic.png';
			$doc['pic'] = $pic;
			$doc['bio'] = $bio;
			$doc['class'] = $class;
			$doc['school'] = $school;
			$E->cEmails->save($doc);
		}
	}
	
	require_once('footer.php');
?>