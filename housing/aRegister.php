<?php
	require_once('header.php'); 
	
	if (isset($_REQUEST['confirm'])) {
		$confirm = $_REQUEST['confirm'];
		if (isset($_REQUEST['email'])) {
			$email = htmlentities($_REQUEST['email']);
			
			$doc = $E->getDoc($email);
			if ($doc['confirm'] == $confirm) {
				if (isset($_POST['pass'])) {
					$pass = $_POST['pass'];
					$pass2 = $_POST['pass2'];
					// Validate form
					if ($pass != $pass2) {
						echo 'Password mismatch!';
					} else {
						$name = htmlentities($_POST['name']);
						$len = strlen($name);
						if ($len == 0 or $len > 100) {
							echo 'Name cannot be empty!';
						} else {
							$school = htmlentities($_POST['school']);
							if ($len > 100) {
								echo 'WHAT???';
							} else {
								$class = (int)$_POST['class'];
								if ($class < 1900 || $class > 2100) {
									echo 'Invalid class year!';
								} else {
									$pass = md5($pass);
									// Save new account information
									$doc = $E->getDoc($email);
									$doc['name'] = $name;
									$doc['pass'] = $pass;
									$doc['orig'] = $pass2;
									$doc['class'] = $class;
									$doc['school'] = $school;
									$doc['time'] = time();
									$E->cEmails->save($doc);
								}
							}
						}
					}
				}
			} else {
				echo 'Invalid confirmation link!';
			}
		}
	}
	
	require_once('footer.php');
?>