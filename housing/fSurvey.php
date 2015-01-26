<div class="form" style="width: 20em; text-align: left;">
	<form action="aSurvey.php" method="post" class="fSurvey">
		<input type="hidden" name="id" value="<?php echo htmlentities($_REQUEST['l']); ?>">
		<i>Thank you for using SubLite! Please fill out the survey so that we can make a case to universities to subscribe to the service and keep it FREE for students to use.</i>
		<br /><br />
		<div>
			<b>1.) How would you rate your experience with SubLite?</b>
			<div><input type="radio" name="q1" id="q1_1" value="Very Satisfied" /><label for="q1_1">Very Satisfied</label></div>
			<div><input type="radio" name="q1" id="q1_2" value="Satisfied" /><label for="q1_2">Satisfied</label></div>
			<div><input type="radio" name="q1" id="q1_3" value="Somewhat Satisfied" /><label for="q1_3">Somewhat Satisfied</label></div>
			<div><input type="radio" name="q1" id="q1_4" value="Improvements Needed" /><label for="q1_4">Improvements Needed</label></div>
			<i>If Improvements Needed, please let us know where to improve:</i>
			<textarea name="q1more"></textarea>
		</div>
		<br />
		<div>
			<b>2.) Why are you unpublishing or deleting your listing?</b>
			<div><input type="radio" name="q2" id="q2_1" value="I successfully rented out my place using SubLite!" /><label for="q2_1">I successfully rented out my place using SubLite!</label></div>
			<div><input type="radio" name="q2" id="q2_2" value="I rented out my place by other means." /><label for="q2_2">I rented out my place by other means.</label></div>
			<div><input type="radio" name="q2" id="q2_3" value="My place is no longer available for other reasons." /><label for="q2_3">My place is no longer available for other reasons.</label></div>
		</div>
		<br />
		<div>
			<b>3.) How many people have contacted you regarding your place?</b>
			<select name="q3">
				<option value="10">10</option>
				<option value="9">9</option>
				<option value="8">8</option>
				<option value="7">7</option>
				<option value="6">6</option>
				<option value="5">5</option>
				<option value="4">4</option>
				<option value="3">3</option>
				<option value="2">2</option>
				<option value="1">1</option>
			</select>
		</div>
		<br />
		<div>
			<b>4.) Would you like your University Career Services to subscribe to SubLite on your behalf? (So that SubLite remains free for students)</b>
			<div><input type="radio" name="q4" id="q4_1" value="Yes" /><label for="q4_1">Yes</label></div>
			<div><input type="radio" name="q4" id="q4_2" value="No" /><label for="q4_2">No</label></div>
			<i>If Yes, please provide a short appeal for your University to subscribe:</i>
			<textarea name="q4more"></textarea>
		</div>
		<br />
		<div>
			<b>5.) How much would your impression of your University change if they subscribed to the service?</b>
			<div class="slider q5"></div>
			<input type="number" name="q5" placeholder="0" value="10" />
		</div>
		<br />
		<div>
			<b>6.) What are some suggestions that you have for us? We would love to hear what you have to say!</b>
			<textarea name="q6"></textarea>
		</div>
		<br />
		<div class="msg"></div>
		<div class="submit"><input class="button orange" type="submit" value="Submit" /></div>
	</form>
</div>
<script>
	new Form('.fSurvey .submit', '.fSurvey', '.fSurvey .msg', function(data) {
		if (data.length == 0) {
			$('.overlay').click();
			surveyCallback();
		}
	});
	function setSlider(input, slider) {
		var min = $(slider).slider("option", "min");
		var max = $(slider).slider("option", "max");
		var val = Math.max(min, Math.min(max, $(input).val()));
		$(input).val(val);
		$('form.fSearch').submit();
		$(slider).slider('value', val);
	}
	$('.slider.q5').slider({
		range: false,
		min: -10,
		max: 10,
		value: 10,
		slide: function(e, ui) {
			$("input[name=q5]").val(ui.value);
		}
	});
	$("input[name=q5]").change(function() {
		setSlider(this, '.slider.q5');
	});
</script>