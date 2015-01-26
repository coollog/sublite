<?php require_once('panelcss.php'); ?>
<?php require_once('formcss.php'); ?>
<style>
	.modal iframe {
		border: 1px solid #ccc;
		margin: 0;
		width: 100%;
		display: block;
		height: 100%;
	}
	.modal .iframe {
		position: relative;
		height: 20em;
		overflow: auto;
		-webkit-overflow-scrolling: touch;
	}
	.accept {
		width: 100%;
	}
</style>
<script>
	var scrolled = false;
	function setupTerms() {
		scrolled = true;
		$('.accept').click(function() {
			if (!scrolled) {
				$('.modal .msg').show();
			} else {
				$('.overlay').click();
				registerHTML = $('.register').html();
				loadRegister($('form.fRegister').serializeObject());
			}
		});
		$('.modal iframe').contents().scroll(function() {
			if ($('.modal iframe').contents().scrollTop() > $('.modal iframe').contents().height() - 2000) {
				scrolled = true;
			}
		});
	}
	var registerHTML;
	function loadRegister(postData) {
		registerHTML = $('.register .submit').html();
		$('.register .submit').html(loadgif);
		$.post('aRegister.php', postData).done(function(data) {
			if (data.length > 0) { // Error
				$('.register .submit').html(registerHTML);
				$('.register input[name=pass]').val('');
				$('.register input[name=pass2]').val('');
				$('.register .msg').show().html(data);
				setupRegister();
			} else { // Auto login
				var email = postData['email'],
					pass = postData['pass'];
				$.post('aLogin.php', {email: email, pass: pass}).done(function(data) {
					window.location.href = "aAfterLogin.php";
				});
			}
		});
	};
	function setupRegister() {
		$('form.fRegister').submit(function() {
			overlay('<div class="iframe"><iframe src="terms.html"></iframe></div><br /><div class="msg">Please scroll through the Terms.</div>I have read, fully understand, and agree to abide by and be bound by SubLite&rsquo;s Terms of Service.<br /><input class="button orange accept" type="button" value="Accept" /><br /><br /><center>Like us on Facebook!</center><script>setupTerms(); $(".fb-like-box").clone().appendTo(".modal center");</sc' + 'ript>');
			return false;
		});
	}
	$(function() {
		$("input[name='name']").focus();
		
		setupRegister();
	});
</script>
<div class="panel" tag="main">
	<div class="content">
		<div class="logo"></div>
		<br /><br />
		<div class="form">
			<div class="register">
				<h3>Register an Account</h3>
				<div class="msg"></div>
				<form method="post" class="fRegister">
					<?php echo $email; ?>
					<input type="hidden" name="email" value="<?php echo $email; ?>" />
					<input type="hidden" name="confirm" value="<?php echo $confirm; ?>" />
					
					<input type="text" name="name" maxlength="100" placeholder="Full Name" />
					Password must be at least 6 characters.
					<input type="password" name="pass" pattern=".{6,}" required placeholder="Password" />
					<input type="password" name="pass2" pattern=".{6,}" required placeholder="Confirm Password" />
					<input type="text" name="class" placeholder="Class Year" />
					If you are a graduate student, enter your school:
					<input type="text" name="school" maxlength="100" placeholder="(eg. Law School, Business School)" />
					
					<div class="submit"><input class="button orange" type="submit" value="Register" /></div>
				</form>
			</div>
		</div>
	</div>
</div>