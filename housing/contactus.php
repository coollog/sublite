<div style="text-align: center;">
	We are still in beta development! Please let us know of any suggestions or bugs you find!<br /><br />
	<div style="display: inline-block; text-align: left;">
		Send us an email at <a href="mailto:info@sublite.net">info@sublite.net</a>
		<h5>OR</h5>
		Send us a message using this form:
		<div class="form">
			<form class="fContactUs" method="post" action="aContactUs.php">
				<input type="text" name="name" placeholder="Your Name" />
				<input type="text" name="from" placeholder="Your Email" />
				<input type="text" name="subject" placeholder="Subject" />
				<textarea name="message" placeholder="Message"></textarea>
				<div class="msg"></div>
				<div class="submit"><input class="button orange" type="submit" value="Send Message" /></div>
			</form>
		</div>
		<script>
			new Form('.fContactUs .submit', 'form.fContactUs', '.fContactUs .msg', function(data) {
				if (data.length == 0) {
					$('.fContactUs input[type=text], .fContactUs textarea').val('');
					$('.fContactUs .msg').html('Your message has been sent! Please allow for 2-3 business days for a response.');
				}
			});
		</script>
	</div>
</div>