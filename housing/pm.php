<?php
	$requireLogin = true;
	require_once('header.php');	
	require_once('htmlheader.php');
	
	$email = $_SESSION['email'];
	$messages = $M->search(array('$or' => array(array("from" => $email), array("to" => $email))));
	
?>

<?php require_once('navbar.php'); ?>

<?php require_once('formcss.php'); ?>
<?php require_once('contentcss.php'); ?>
<style>
	input {
		display: block;
	}
	
	.new {
		display: none;
	}
</style>
<script>	
	/*$(function() {
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
	});	*/
	
	$(function() {
		new Form('.new .buttons', '.new form', '.newmessagemsg', function(data) {
			if (data.length == 0) {
				reset();
				$('.newmessagemsg').html('Message sent!');
			}
		});
		$('.new input[name=cancel]').click(function() {
			reset();
		});
		$('#newmessage').click(function() {
			$(this).hide();
			$('.new').show();
		});
	});
	function reset() {
		$('.new form')[0].reset();
		$('.new').hide();
		$('#newmessage').show();
	}
	function setupCancel() {
		$('.save input[name=cancel]').click(function() {
			reset();
			$(this).parent().submit();
			showCancel(false);
		});
	}
</script>

<div class="fillcontent">
	<div class="contentblock messagetop" style="display: block;">
		<input id="newmessage" class="button orange" type="button" value="New Message" /></a>
		<div class="new">
			<form method="post" action="aMessage.php">
				<input type="hidden" name="type" value="new" />
				<input type="text" name="to" placeholder="Recipient Email" />
				<input type="text" name="subject" placeholder="Subject" />
				<textarea name="body" placeholder="Message (1000 chars)" maxlength="1000"></textarea>
				<div class="buttons">
					<input class="button orange" type="button" name="cancel" value="Cancel" />
					<input class="button orange" type="submit" name="send" value="Send" />
				</div>
			</form>
		</div>
		<div class="newmessagemsg"></div>
	</div>
	<div class="contentblock messagebottom" style="display: block;">
		<h3>Messages</h3>
		<div class="messages">
			You currently have no messages.<br />
			<?php
				if ($messages->count() > 0) {
					echo "<script>$('.messages').html('');</script>";
					foreach ($messages as $msg) {
						require('iBlockMessage.php');
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