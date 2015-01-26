<?php
	$requireLogin = true;
	require_once('header.php');
	
	if (isset($_POST['type'])) {
		$type = $_POST['type'];
		if ($type == 'new') {
			$toemail = $_POST['to'];
			$to = $E->getDoc($toemail);
			if (!$to or !($p = $E->getProfile($to['_id']))) {
				echo "User doesn't exist!";
			} else {
				$body = clean($_POST['body']);
				$subject = clean($_POST['subject']);
				if (strlen($body) == 0) {
					echo 'Message cannot be blank!';
				} else {
					if (strlen($subject) == 0) {
						echo 'Subject cannot be blank!';
					} else {
						$listing = isset($_POST['listing']) ? $_POST['listing'] : false;
						$msg = array(
							'from' => $_SESSION['email'],
							'to' => $toemail,
							'subject' => $subject,
							'body' => $body,
							'time' => time(),
							'listing' => $listing,
							'replies' => array()
						);
						$M->add($msg);
					}
				}
			}
		}
		if ($type == 'reply') {
			$id = $_POST['id'];
			if (!($thread = $M->get($id))) {
				echo 'Replying to non-existant thread!';
			} else {
				$body = clean($_POST['body']);
				if (strlen($body) == 0) {
					echo 'Message cannot be blank!';
				} else {
					$msg = array(
						'from' => $_SESSION['email'],
						'body' => $body,
						'time' => time()
					);
					$M->reply($id, $msg);
				}
			}
		}
	}
	
	require_once('footer.php');
?>