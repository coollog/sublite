<?php
	$requireLogin = true;
	require_once('header.php');	
	require_once('htmlheader.php');
?>

<?php require_once('navbar.php'); ?>

<?php require_once('panelcss.php'); ?>
<style>
	input {
		display: block;
	}
	img.img {
		height: 100px;
		max-width: 20em;
		margin: 0.5em;
		cursor: pointer;
		<?php cssCross('transition: all 0.3s ease-in-out;'); ?>
	}
	img.img:hover {
		opacity: 0.5;
	}
	.imgs .remove {
		display: none;
		margin: 0 auto;
	}
</style>
<script>
	$(function() {		
		new Form('.list .submit', 'form.fList', '.list .msg', function(data) {
			if (data.length == 0) {
				window.location.href = 'aJustListed.php';
				$('.list .submit').hide();
			}
		});
		
		$("input[name='from'], input[name='to']").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: 0,
			onClose: function(selectedDate) {
				if ($(this).attr('name') == 'from') {
					$("input[name='to']").datepicker("option", "minDate", selectedDate);
				}
			}
		});
	});
	function addImg(url) {
		if ($('.imgs').html() == '<i>None</i>') {
			$('.imgs').html('');
		}
		$('.imgs')
			.append('<input class="img" type="hidden" name="img[]" value="' + url + '" />')
			.append('<img class="img" src="' + url + '" />')
			.append('<input class="button green remove" url="' + url + '" value="Remove Photo" />');
		$('img.img').each(function() {
			if ($(this).attr('src') == url) {
				$(this).click(function() {
					var url = $(this).attr('src');
					if ($(this).height() == 100) {
						$(this).height(200);
						$('.imgs .remove').each(function() {
							if ($(this).attr('url') == url) $(this).stop().fadeIn(300, "easeInOutCubic");
						});
					} else {
						$(this).height(100);
						$('.imgs .remove').each(function() {
							if ($(this).attr('url') == url) $(this).stop().fadeOut(300, "easeInOutCubic");
						});
					}
				});
			}
		});
		$('.imgs .remove').each(function() {
			if ($(this).attr('url') == url) {
				$(this).click(function() {
					var url = $(this).attr('url');
					$('.imgs .img').each(function() {
						if ($(this).val() == url) $(this).remove();
					});
					$('img.img').each(function() {
						if ($(this).attr('src') == url) $(this).remove();
					});
					$('.imgs .remove').each(function() {
						if ($(this).attr('url') == url) $(this).remove();
					});		
					if ($('.imgs').html() == '') {
						$('.imgs').html('<i>None</i>');
					}
				});
			}
		});
	}
</script>

<div class="panel" tag="main">
	<div class="content">
		<div class="list">
			<h3>Create a Listing</h3>
			<br />
			<div class="msg"></div>
			<div class="form">
				<form method="post" class="fList" action="aList.php">
					<input type="text" name="location" placeholder="Address" />
					<input type="text" name="city" placeholder="City" />
					<input type="text" name="state" placeholder="State" />
					<input type="text" name="from" placeholder="From" />
					<input type="text" name="to" placeholder="To" />
					$ <input style="width: 70%; display: inline-block;" type="number" name="price" placeholder="Price ($ per week)" /> per <strong>week</strong>
					<input type="text" name="title" placeholder="Title" maxlength="100" />
					<textarea name="summary" placeholder="Summary (1000 chars)" maxlength="1000"></textarea>
					<input type="number" name="occ" placeholder="Max Occupancy" />
					<?php require('fRoom.php'); ?>
					<?php require('fBuilding.php'); ?>
					<?php require('fAmenities.php'); ?>
					<h5>Upload Photos</h5>
					<div class="iframe"><iframe class="S3Upload" src="S3Upload.php"></iframe></div>
					<h5>Current Photos</h5>
					<div class="imgs"><i>None</i></div>
					<br />
					<input type="checkbox" name="tos" value="tos" style="width: 1em;" id="tos"><label for="tos"><small>I have read, fully understand, and agree to SubLite&rsquo;s <a href="#terms">Terms of Service</a>. I represent and warrant that I have permission to list this property, and that the description is accurate and not misleading. I will negotiate the terms of the stay with potential guests in good-faith.</small></label>
					<div class="submit"><input class="button orange" type="submit" value="Post Listing" /></div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>