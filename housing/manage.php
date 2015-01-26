<?php require_once('header.php'); 
	  require_once('htmlheader.php'); ?>

<style>
	textarea {
		width: 400px;
		height: 200px;
		display: block;
	}
</style>
<script>
	function loadEmails() {
		$('.existing').html('Loading...');
		$.post('aGetEmails.php').done(function(data) {
			$('.existing').html(data);
		});
	};
	$(function() {
		$('input[type=submit]').button();
		
		new Form('.add .submit', 'form.fAdd', '.add .msg', function() {loadEmails();});
		new Form('.remove .submit', 'form.fRemove', '.remove .msg', function() {loadEmails();});
		
		loadEmails();
	});
</script>

<div class="add">
	<h3>Add Emails</h3>
	<div class="msg"></div>
	<form method="post" class="fAdd" action="aManage.php">
		<textarea name="emails"></textarea>
		<div class="submit"><input type="submit" value="Add Emails" /></div>
	</form>
</div>

<h3>Existing Emails</h3>
<pre class="existing"></pre>

<div class="remove">
	<h3>Remove Emails</h3>
	<div class="msg"></div>
	<form method="post" class="fRemove" action="aManage.php">
		<textarea name="remails"></textarea>
		<div class="submit"><input type="submit" value="Remove Emails" /></div>
	</form>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>