<?php
	$requireLogin = true;
	require_once('header.php');	
	require_once('htmlheader.php');
	
	$p = $E->getDoc($_SESSION['email']);

	$id = $p['_id'];
	$email = $p['email'];
	$name = $p['name'];
	$pic = 'noprofilepic.png';
	if (isset($p['pic'])) {
		$pic = $p['pic'];
		if ($pic == 'nopic.png') $pic = 'noprofilepic.png';
	}
	$school = $S->nameOf($email);
	$bio = 'Welcome to my profile!';
	if (isset($p['bio'])) {
		$bio = $p['bio'];
	}
	$class = '';
	if ($p['class'] > 0)
		$class = $p['class'];
	$school = 'Undergraduate';
	if (isset($p['school']) and strlen($p['school']) > 0) {
		$school = $p['school'];
	}
	$listings = $L->search(array("email" => $email));
	
	// Can edit name, pic, bio
?>

<?php require_once('navbar.php'); ?>

<?php require_once('formcss.php'); ?>
<?php require_once('contentcss.php'); ?>
<style>
	input {
		display: block;
	}
	.edit .val, .editArea .val {
		min-width: 100px;
		min-height: 1em;
		cursor: pointer;
	}
	.edit .val, .edit input {
		display: inline-block;
	}
	.edit[name=pic] .val {
		width: 250px;
		height: 250px;
		background: #fff no-repeat center center;
		background-size: contain;
		max-width: 100%;
	}
	.save form {
		display: inline-block;
	}
	.imgurwrapper {
		display: none;
	}
</style>
<script>
	var p = new Array();
	p['pic'] = "<?php echo JSfriend($pic); ?>";
	p['name'] = "<?php echo JSfriend($name); ?>";
	p['class'] = "<?php echo JSfriend($class); ?>";
	p['school'] = "<?php echo JSfriend($school); ?>";
	p['bio'] = "<?php echo JSfriend($bio); ?>";
	
	$(function() {
		$('.save .msg').show();
		
		new Form('.logout .submit', '.logout form', '.logout .msg', function(data) {
			window.location.href = ".";
		});
	
		$('input[name=savechanges], input[name=cancel]').hide();
		function reset() {
			$('.save input[type=hidden]').each(function() {
				$(this).val(p[$(this).attr('name')]);
			});
			$('.editArea input').hide();
			$('.edit input, .editArea textarea').hide().each(function() {
				$(this).val(p[$(this).parent().attr('name')]);
			});
			$('.edit .val, .editArea .val').show().each(function() {
				var val = p[$(this).parent().attr('name')];
				$(this).html(val);
				if ($(this).parent().attr('name') == 'class') {
					$(this).html('Class of ' + val);
				}
			});
			$('.edit[name=pic] .val').html('').css('background-image', "url(" + p['pic'] + ")");
			$('.imgurwrapper').hide();
		}
		function setupCancel() {
			$('.save input[name=cancel]').click(function() {
				reset();
				$(this).parent().submit();
				showCancel(false);
			});
		}
		
		new Form('.save .buttons', '.save form', '.save .msg', function(data) {
			setupCancel();
			showSave(false);
			if (data.length == 0) {
				$('.save .msg').html('Profile saved!');
			}
		});
		$('.edit input').hide().keypress(function (e) {
			if (e.which == 13) {
				var val = $(this).val();
				var name = $(this).parent().attr('name');
				$(this).hide().parent().children('.val').html(val).show();
				if ($(this).parent().attr('name') == 'class') {
					$(this).parent().children('.val').html('Class of ' + val);
				}
				$('.save input[name=' + name + ']').val(val);
				showSave(true);
			}
		});
		$('.edit .val').click(function() {
			$(this).hide().parent().children('input').show();
			showCancel(true);
			$('.save .msg').html('Press enter to update the field!');
		});
		$('.edit[name=pic] .val').click(function() {
			$('.imgurwrapper').html('<iframe class="imgur" src="imgur.php"></iframe>').show();
			$('.save input[name=pic]').val('noprofilepic.png');
			showSave(true);
			showCancel(true);
		});
		$('.editArea input').click(function() {
			var val = $(this).parent().children('textarea').val();
			var name = $(this).parent().attr('name');
			$(this).parent().children('textarea').hide();
			$(this).hide().parent().children('.val').html(val).show();
			$('.save input[name=' + name + ']').val(val);
			showSave(true);
		});
		$('.editArea .val').click(function() {
			$(this).hide().parent().children('textarea, input').show();
			showCancel(true);
		});
		
		setupCancel();
		reset();
	});
	function showSave(show) {
		if (show) {
			$('input[name=savechanges]').show();
			$('.save .msg').html('Press Save Changes to save your changes!');
		} else
			$('input[name=savechanges]').hide();
	}
	function showCancel(show) {
		if (show)
			$('input[name=cancel]').show();
		else
			$('input[name=cancel]').hide();
	}
	function addImg(url) {
		$('.save input[name=pic]').val(url);
		$('.edit[name=pic] .val').css('background-image', "url(" + url + ")").show();
		$('.imgurwrapper').hide();
		$('.save .pic').val(url);
		showSave(true);
	}
</script>

<div class="fillcontent">
	<div class="contentblock profileleft">
		<a href="profile.php?p=<?php echo $id; ?>"><input class="button orange" type="button" value="View Public Profile" /></a>
		<div class="logout">
			<form method="post" action="aLogout.php">
				<div class="submit"><input class="button green" type="submit" value="Logout" /></div>
			</form>
		</div>
		<div class="save">
			<form method="post" action="aAccount.php">
				<input type="hidden" name="pic" />
				<input type="hidden" name="name" />
				<input type="hidden" name="bio" />
				<input type="hidden" name="class" />
				<input type="hidden" name="school" />
				<input type="hidden" name="save" />
				<div class="buttons">
					<input class="button orange" type="button" name="cancel" value="Cancel" />
					<input class="button orange" type="submit" name="savechanges" value="Save Changes" />
				</div>
			</form>
			<div class="msg">Click what you want to edit!</div>
		</div>
		<h3><div class="edit" name="name">
			<input type="text" placeholder="Name" maxlength="100" />
			<div class="val"></div>
		</div></h3>
		<div class="edit" name="pic">
			<div class="imgurwrapper iframe"></div>
			<div class="val"></div>
		</div>
		<br />
		<div class="edit" name="school">
			<input type="text" placeholder="Graduate School?" maxlength="100" />
			<div class="val"></div>
		</div>
		<div class="edit" name="class">
			<input type="text" placeholder="Class Year" maxlength="100" />
			<div class="val"></div>
		</div>
		<br />
		<div class="editArea" name="bio">
			<textarea name="bio" placeholder="Bio (250 chars)" maxlength="250"></textarea>
			<input class="button orange" type="button" value="Update" />
			<div class="val"></div>
		</div>
	</div>
	<div class="contentblock profileright">
		<h3>Edit a Listing</h3>
		<div class="listings">
			You currently have no listings. Make one?<br />
			<a href="list.php"><input class="button orange" type="button" value="Create a Listing"></a>
			<?php
				if ($listings->count() > 0) {
					echo "<script>$('.listings').html('');</script>";
					$link = 'edit.php';
					foreach ($listings as $l) {
						$id = $l['_id'];
						$title = $l['title'];
						$price = $l['price'];
						$summary = $l['summary'];
						$img = 'nopic.png';
						if (isset($l['imgs'][0])) {
							$img = $l['imgs'][0];
						}
						require('iBlockProfile.php');
					}
				}
			?>
		</div>
	</div>
</div>

<?php	
	require_once('htmlfooter.php'); 
    require_once('footer.php'); 
?>