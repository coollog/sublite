<?php
	$requireLogin = true;
	require_once('header.php');
	
	if (isset($_POST['l'])) {
		$id = $_POST['l'];
		$comment = trim(utf8_encode(nl2br(htmlentities($_POST['comment']))));
		if (strlen($comment) == 0) {
			echo 'Comment cannot be blank!';
		} else {
			$L->addComment($id, $_SESSION['email'], $comment);
		}
	}
	
	require_once('footer.php');
?>