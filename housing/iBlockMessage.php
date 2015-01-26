<?php
	$id = $msg['_id'];
	$from = $msg['from'];
	$to = $msg['to'];
	$subject = $msg['subject'];
	$body = $msg['body'];
	$time = $msg['time'];
	$replies = $msg['replies'];
	
	require_once('blockmessagecss.php');
?>
<div class="block">
	<div class="from"><?php echo $from; ?></div>
	<div class="to"><?php echo $to; ?></div>
	<div class="subject"><?php echo $subject; ?></div>
</div>