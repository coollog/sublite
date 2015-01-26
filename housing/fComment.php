<?php
	$requireLogin = true;
	require_once('header.php');
	
	if (isset($_REQUEST['l'])) { 
		$id = $_REQUEST['l'];
		$l = $L->get($id);
		if ($l != NULL) {
?>

<?php if (isset($_SESSION['email'])) { ?>
	<style>
		.addComment {
			min-width: 50%;
			margin: 0;
		}
		.commenttext {
			width: 100%;
			height: 5em;
		}
	</style>
	<div class="form addComment">
		<form method="post" class="fComment" action="aComment.php">
			<input type="hidden" name="l" value="<?php echo htmlentities($id); ?>" />
			
			<textarea class="commenttext" name="comment" placeholder="Add a Comment"></textarea>
			
			<div class="msg"></div>		
			<div class="submit"><input class="button orange" type="submit" value="Post" /></div>
		</form>
	</div>
	<script>
		new Form('.addComment .submit', 'form.fComment', '.addComment .msg', function(data) {
			if (data.length == 0) {
				loadComments();
			}
		});
	</script>
<?php } ?>

<style>
	.commentblock {
		padding: 0.5em 0;
	}
	.commenttime {
		opacity: 0.7;
		display: inline-block;
		margin-left: 1em;
		font-size: 0.9em;
	}
	.commentbody {
		margin: 0.25em 0;
	}
</style>
<div class="commentList">
	<?php
		$comments = array_reverse($l['comments']);
		
		foreach ($comments as $comment) {
			$commenter = $E->getDoc($comment['email']);
			$cid = $commenter['_id'];
			$name = $commenter['name'];
			$commenttext = $comment['comment'];
			$time = $comment['time'];
	?>
			<div class="commentblock">
				<div class="commenter">
					<a href="profile.php?p=<?php echo $cid; ?>"><?php echo $name; ?></a>
					<div class="commenttime"><?php echo timeAgo($time); ?></div>
				</div>
				<div class="commentbody"><?php echo $commenttext; ?></div>
			</div>
	<?php
		}
	?>
</div>

<?php 
		}
	}
	require_once('footer.php');
?>