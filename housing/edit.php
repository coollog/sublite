<?php
	$requireLogin = true;
	require_once('header.php');	
	require_once('htmlheader.php');
	
	if (!isset($_GET['l'])) {
		echo 'Invalid ID!';
	} else {
		$id = $_GET['l'];
		if (!($l = $L->get($id))) {
			echo "Listing doesn't exist!";
		} else {
			if ($l['email'] != $_SESSION['email']) {
				echo 'Not your listing!';
			} else {
				$l['from'] = date('n/j/Y', $l['from']);
				$l['to'] = date('n/j/Y', $l['to']);
				$l['title'] = html_entity_decode($l['title']);
				$l['location'] = html_entity_decode($l['location']);
				$l['city'] = html_entity_decode($l['city']);
				$l['state'] = html_entity_decode($l['state']);
				$l['summary'] = html_entity_decode(strip_tags($l['summary']));
				
				setJSVar("publish", $l['publish'] ? 'true' : 'false');
?>

<?php require_once('navbar.php'); ?>

<?php require_once('panelcss.php'); ?>
<style>
	input {
		display: block;
	}
	.publish, .unpublish {
		display: none;
	}
	.imgsDisplay img {
		height: 4em;
	}
	.edit .val, .editArea .val {
		min-width: 100px;
		min-height: 1em;
		cursor: pointer;
	}
	.edit .val, .edit input {
		display: inline-block;
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
	var v = new Array();
	v['l'] = "<?php echo JSfriend($id); ?>",
	v['title'] = "<?php echo JSfriend($l['title']); ?>";
	v['price'] = "<?php echo JSfriend($l['price']); ?>";
	v['occ'] = "<?php echo JSfriend($l['occ']); ?>";
	v['location'] = "<?php echo JSfriend($l['location']); ?>";
	v['city'] = "<?php echo JSfriend($l['city']); ?>";
	v['state'] = "<?php echo JSfriend($l['state']); ?>";
	v['from'] = "<?php echo JSfriend($l['from']); ?>";
	v['to'] = "<?php echo JSfriend($l['to']); ?>";
	v['summary'] = "<?php echo JSfriendTextarea($l['summary']); ?>";
	
	var surveyCallback;
	function setupSurvey() {
		$('.modal').load('fSurvey.php?l=<?php echo $id; ?>', function() {setupModal();});
	}
	function showSurvey(callback) {
		surveyCallback = callback;
		overlay('<img src="gfx/load.gif"><script>setupSurvey();</sc' + 'ript>');
	}
	
	$(function() {
		function reset() {
			$('.list input, .list textarea').each(function() {
				if (v[$(this).attr('name')] != undefined)
					$(this).val(v[$(this).attr('name')]);
			});
			<?php
				foreach ($l['imgs'] as $img) {
					echo "addImg('$img');";
				}
			?>
		}
		
		function updatePublish() {
			if (publish) {
				$('.unpublish').show();
				$('.publish').hide();
				$('.public').show();
			} else {
				$('.publish').show();
				$('.unpublish').hide();
				$('.public').hide();
			}
		}
		updatePublish();
		
		$('.public').click(function() {
			window.location.href = 'listing.php?l=' + v['l'];
		});
		
		new Form('.publish .submit', '.publish form', '.publish .msg', function(data) {
			if (data.length == 0) {
				publish = true;
				updatePublish();
				$('.unpublish .msg').html('Published!');
			}
		});
		new Form('.unpublish .submit', '.unpublish form', '.unpublish .msg', function(data) {
			if (data.length == 0) {
				publish = false;
				updatePublish();
				$('.publish .msg').html('Unpublished!');
			}
		});
		new Form('.del .submit', '.del form', '.del .msg', function(data) {
			if (data.length == 0) {
				window.location.href = 'account.php';
			}
		});
		$('.unpublish .submit input').click(function() {
			showSurvey(function() {$('.unpublish form').submit();});
			return false;
		});
		$('.del .submit input').click(function() {
			showSurvey(function() {$('.del form').submit();});
			return false;
		});
		new Form('.list .submit', '.list form', '.list .msg', function(data) {
			if (data.length == 0) {
				$('.list .msg').html('Saved!');
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

		reset();
	});
	
	function addImg(url) {
		if ($('.imgs').html() == 'None') {
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
						$('.imgs').html('None');
					}
				});
			}
		});
	}
</script>

<div class="panel" tag="main">
	<div class="content">
		<div class="form">
			<div class="public">
				<input class="button orange viewpublic" type="button" value="View Public Listing" />
				<br />
				<small>Share your listing with this link: <a href="http://sublite.net/housing/listing.php?l=<?php echo $id; ?>">http://sublite.net/housing/listing.php?l=<?php echo $id; ?></a></small>
			</div>
			<h3>Edit Listing</h3>
			<h5>Options</h5>
			<div class="publish">
				<div class="msg"></div>
				<form method="post" action="aList.php">
					<input type="hidden" name="l" value="<?php echo $id; ?>" />
					<input type="hidden" name="publish" />
					<div class="submit"><input class="button orange" type="submit" value="Publish" /></div>
				</form>
			</div>
			<div class="unpublish">
				<div class="msg"></div>
				<form method="post" action="aList.php">
					<input type="hidden" name="l" value="<?php echo $id; ?>" />
					<input type="hidden" name="unpublish" />
					<div class="submit"><input class="button orange" type="submit" value="Unpublish" /></div>
				</form>
			</div>
			<div class="del">
				<div class="msg"></div>
				<form method="post" action="aList.php">
					<input type="hidden" name="l" value="<?php echo $id; ?>" />
					<input type="hidden" name="del" />
					<div class="submit"><input class="button green" type="submit" value="Delete Listing" /></div>
				</form>
			</div>
			<div class="list">
				<form method="post" action="aList.php">
					<input type="hidden" name="l" />
					<h5>Title</h5>
					<input type="text" name="title" placeholder="Title" maxlength="100" />
					<h5>Address, City, State</h5>
					<input type="text" name="location" placeholder="Specific Address" />
					<input type="text" name="city" placeholder="City" />
					<input type="text" name="state" placeholder="State" />
					<h5>Availability</h5>
					<input type="text" name="from" placeholder="From" />
					to
					<input type="text" name="to" placeholder="To" />
					<h5>Price ($ per <strong>week</strong>)</h5>
					$ <input style="width: 90%; display: inline-block;" type="number" name="price" placeholder="Price ($ per week)" />
					<h5>Max Occupancy</h5>
					<input type="number" name="occ" placeholder="Max Occupancy" />
					<?php require('fRoom.php'); ?>
					<?php require('fBuilding.php'); ?>
					<?php require('fAmenities.php'); ?>
					<script>
						$('select[name=room]').val(<?php echo '"' . JSFriend($l['room']) . '"'; ?>);
						$('select[name=building]').val(<?php echo '"' . JSFriend($l['building']) . '"'; ?>);
						var amenities = [
							<?php
								$tot = count($l['amenities']);
								for ($i = 0; $i < $tot; $i ++) {
									$text = $l['amenities'][$i];
									echo '"' . JSFriend($text) . '"';
									if ($i < $tot - 1)
										echo ',';
								}
							?>
						];
						$('input[type=checkbox]').each(function() {
							for (var i = 0; i < amenities.length; i ++) {
								if ($(this).val() == amenities[i]) {
									$(this).prop('checked', true);
								}
							}
						});
					</script>
					<h5>Summary</h5>
					<textarea name="summary" placeholder="Summary (1000 chars)" maxlength="1000"></textarea>
					<h5>Upload Photos</h5>
					<div class="iframe"><iframe class="S3Upload" src="S3Upload.php"></iframe></div>
					<h5>Current Photos</h5>
					<div class="imgs">None</div>
					
					<input type="hidden" name="save" />
					<input type="hidden" name="tos" />
					<div class="msg"></div>
					<div class="submit"><input class="button orange" type="submit" name="savechanges" value="Save Changes" /></div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
			}
		}
	}
	
	require_once('htmlfooter.php'); 
    require_once('footer.php'); 
?>