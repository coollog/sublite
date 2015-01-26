<?php
	require_once('header.php');
	
	if (isset($_SESSION['email'])) {
		header("Location: http://$domain/search.php");
		exit();
	}
	
	require_once('htmlheader.php');
?>

<style>
	body, html {
		font-size: 14px;
	}
	.panel {
		padding: 1em 0 4em 0;
		background: #efeeee;
		line-height: 
		text-align: center;
	}
	.panel h3 {
		font-size: 1.3em;
		font-weight: normal;
		margin: 2em 0 0.5em 0;
	}
	.panel .content {
		max-width: 50em;
		margin: 0 auto;
	}
	.panel .logo {
		display: inline-block;
		width: 100%;
		height: 4em;
		background: url('gfx/logo.png') no-repeat center center;
		background-size: contain;
	}
	.panel .form {
		max-width: 20em;
		margin: 0 auto;
		text-align: center;
	}
	.panel .form input:not([type=submit], [type=button]) {
		<?php cssCross('transition: all 0.30s ease-in-out;'); ?>
		outline: none;
		padding: 3px 0px 3px 3px;
		margin: 5px 1px 3px 0px;
		border: 1px solid #DDDDDD;
		width: 100%;
	}
	.panel .form input:not([type=submit], [type=button]):focus {
		box-shadow: 0 0 5px rgba(81, 203, 238, 1);
		padding: 3px 0px 3px 3px;
		margin: 5px 1px 3px 0px;
		border: 1px solid rgba(81, 203, 238, 1);
	}
	.panel .form input[type=submit] {
		width: 100%;
		height: 2em;
	}
	.panel .email .extension {
		display: inline;
	}
	/*.hidden {
		display: none;
	}*/
</style>
<script>
	$(function() {
		buttonset('.selschool');
	});
</script>

<div class="panel">
	<div class="content">
		<div class="logo"></div>
		<br /><br />
		<div class="form">
			<h3>What's your school?</h3>
			<div class="selschool">
				<input type="radio" id="school1" name="school" value="yale" checked="checked"><input type="button" for="school1" value="Yale University" />
				<input type="radio" id="school2" name="school" value="upenn"><input type="button" for="school2" value="University of Pennsylvania" />
			</div>
			<div class="email hidden">
				<h3>What's your email address?</h3>
				<div class="msg"></div>
				<form method="post" class="fVerify">
					<input type="hidden" name="extension" />
					<input type="text" name="email" style="width: 50%;" />@<div class="extension"></div>.edu<br />
					<input type="submit" value="Verify" />
				</form>
			</div>
			<div class="login hidden">
				<h3>Login</h3>
				<div class="msg"></div>
				<form method="post" class="fLogin">
					<div class="ea"></div>
					<input type="hidden" name="email" />
					
					<input type="password" name="pass" placeholder="Password" />
					<input type="submit" value="Login" />
				</form>
			</div>
			<div class="confirm hidden"><br /><br />
				A confirmation email has been sent to <div class="ea"></div>. Check your inbox.
			</div>
		</div>
	</div>
</div>