<?php
	if (isset($_GET{'login'})) {
		$requireLogin = true;
	}
	require_once('header.php');	
	
	if (!isset($_GET['l'])) {
		error('Invalid ID!');
	} else {
		$id = $_GET['l'];
		if (!($l = $L->get($id))) {
			error("Listing doesn't exist!");
		} else {
			if (!isset($l['publish']) or !$l['publish']) {
				error("Listing is not published!");
			} else {
				$from = date('n/j/Y', $l['from']);
				$to = date('n/j/Y', $l['to']);
				
				setJSVar("publish", $l['publish'] ? 'true' : 'false');
				
				// Get lister information
				$p = $E->getDoc($l['email']);
				$pid = $p['_id'];
				$email = $p['email'];
				$name = $p['name'];
				$class = '';
				if ($p['class'] > 0)
					$class = "Class of " . $p['class'];
				$school = 'Undergraduate';
				if (strlen($p['school']) > 0) {
					$school = $p['school'];
				}
				$pic = 'noprofilepic.png';
				if (isset($p['pic'])) {
					$pic = $p['pic'];
				}
				$college = $S->nameOf($email);
				$bio = 'Welcome to my profile!';
				if (isset($p['bio'])) {
					$bio = $p['bio'];
				}
				
				// Headers
				$title = 'Student Sublet &ndash; $' . $l['price'] . '/wk &ndash; ' . $l['title'] . ' &ndash; ' . getCity($l['location'] . ', ' . $l['city'] . ', ' . $l['state']);
				$url = getURL();
				$desc = $l['summary'];
				$customheaders = '
					<title>' . $title . '</title>
					<meta property="og:title" content="'.JSFriend($title).'" />
					<meta property="og:type" content="article" />
					<meta property="og:url" content="'.$url.'" />
					<meta property="og:description" content="'.JSFriend($desc).'" /> 
					<meta property="og:site_name" content="SubLite" />
					<meta property="fb:app_id" content="478408982286879" />
					<meta property="fb:admins" content="carscanflynow" />
				';
				foreach ($l['imgs'] as $img) {
					$customheaders .= '<meta property="og:image" content="'.https($img).'" />';
				}
				if (count($l['imgs']) == 0) {
					$customheaders .= '<meta property="og:image" content="https://sublite.net/gfx/logosquare.png" />';
				}
				
				// Is Facebook bot?
				if (isset($_SESSION['email'])) {
					$me = $E->getDoc($_SESSION['email']);
					$myName = $me['name'];
				} else $myName = 'Facebook';
				
				// Is self?
				if (isset($_SESSION['email']))
					$isSelf = ($email == $_SESSION['email']);
				else
					$isSelf = false;
					
				// Update view count
				$l['views'] ++;
				$L->save($l);
				
				require_once('htmlheader.php');
?>

<?php require_once('navbar.php'); ?>

<?php require_once('contentcss.php'); ?>
<style>
	.pic {
		background: #fff no-repeat center center;
		background-image: url('<?php echo $pic; ?>');
		width: 250px;
		height: 250px;
		background-size: contain;
		display: inline-block;
		max-width: 100%;
	}
	.listing {
		text-align: left;
	}
	.profilelink {
		color: #111;
	}
	.title {
		text-align: center;
	}
	.from, .to {
		display: inline-block;
	}
	.email {
		display: none;
		word-wrap: break-word;
	}
	.listing h5 {
		margin-bottom: 0.5em;
		font-weight: bold;
	}
	.imgs {
		height: 20em;
		text-align: center;
		padding: 1em;
		border: 1px solid #ccc;
		overflow: hidden;
		overflow-x: auto;
		white-space: nowrap;
		position: relative;
		display: none;
		-webkit-overflow-scrolling: touch;
	}
	.imgsnone {
		border: 1px solid #ccc;
		padding: 1em;
		text-align: center;
	}
	.imgs .scroll {
		position: absolute;
		width: 4em;
		top: 0; bottom: 0;
		background: rgba(255, 255, 255, 0.2) no-repeat center center;
		background-size: 4em;
		z-index: 5;
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
		opacity: 0.5;
	}
	.imgs .scroll:hover {
		opacity: 1;
	}
	.imgs .scrollleft {
		left: 0;
		background-image: url('gfx/left.png');
	}
	.imgs .scrollright {
		right: 0;
		background-image: url('gfx/right.png');
	}
	.imgs img {
		display: inline-block;
		height: 100%;
		margin-right: 1em;
		cursor: pointer;
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
	}
	.imgs img:hover {
		opacity: 0.5;
	}
	.location {
		font-size: 2em;
		display: inline-block;
		line-height: 2em;
		text-align: left;
	}
	.location div {
		font-size: 0.7em;
		display: inline-block;
	}
	.price {
		font-size: 3em;
		font-weight: bolder;
		display: inline-block;
		float: right;
		line-height: 2em;
	}
	
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
		margin: 0.5em 0;
	}
</style>
<script>
	var v = new Array(), p = new Array();
	v['l'] = "<?php echo JSfriend($id); ?>";
	v['title'] = "<?php echo JSfriend($l['title']); ?>";
	v['price'] = "<?php echo JSfriend($l['price']); ?>";
	v['occ'] = "<?php echo JSfriend($l['occ']); ?>";
	v['room'] = "<?php echo JSfriend($l['room']); ?>";
	v['building'] = "<?php echo JSfriend($l['building']); ?>";
	v['address'] = "<?php echo JSfriend($l['location']); ?>";
	v['city'] = "<?php echo JSfriend($l['city']); ?>";
	v['state'] = "<?php echo JSfriend($l['state']); ?>";
	v['from'] = "<?php echo JSfriend($from); ?>";
	v['to'] = "<?php echo JSfriend($to); ?>";
	v['summary'] = "<?php echo JSfriend($l['summary']); ?>";
	p['id'] = "<?php echo JSfriend($pid); ?>";
	p['name'] = "<?php echo JSfriend($name); ?>";
	p['school'] = "<?php echo JSfriend($school); ?>";
	p['class'] = "<?php echo JSfriend($class); ?>";
	p['college'] = "<?php echo JSfriend($college); ?>";
	p['email'] = "<?php echo JSfriend($email); ?>";
	p['bio'] = "<?php echo JSfriend($bio); ?>";
	
	$(function() {
		$('.listing div').each(function() {
			var c = $(this).attr('class');
			if (c == 'price') {
				$(this).html('$' + v[c] + '<smaller>/wk</smaller>');
			} else {
				$(this).html(v[c]);
			}
		});
		$('.lister div').each(function() {
			$(this).html(p[$(this).attr('class')]);
		});
		$('.profilelink').attr('href', 'profile.php?p=' + p['id']);
		<?php
			foreach ($l['imgs'] as $img) {
				$img = https($img);
				echo "addImg('$img');";
			}
		?>
		
		$('.lister input[type=button]').click(function() {
			overlay('<div class="iframe"><iframe src="terms.html"></iframe></div><br />I have read, fully understand, and agree to Sublite&rsquo;s Terms of Service. I agree to contact the host in good-faith to inquire about the listing.<br /><input class="button orange accept" type="button" value="Accept" /><script>setupTerms();</sc' + 'ript>');
		});
	});
	
	function setupTerms() {
		$('.accept').click(function() {
			//$('.overlay').click();
			//$('.lister .email').show();
			//$('.lister input[type=button]').parent().hide();
			sendInquiry($('.lister .email'));
		});
	}
	function openSelf(img) {
		var url = $(img).attr('src'); console.log(url);
		overlay('<img src="' + url + '" style="max-height: 100%; max-width: 100%;" />');
	}
	function addImg(url) {
		$('.imgs').show().append('<img onclick="openSelf(this);" src="' + url + '" />');
		$('.imgsnone').hide();
	}
	
	function sendInquiry(element) {
		var email = $(element).html();
		overlay(
			//'Email the owner at <a href="mailto: ' + email + '">' + email + '</a>.' +
			//'<h5>OR</h5>' +
			'Send an inquiry to the owner:' +
			'<div class="form" style="width: 20em;">' +
			'	<form class="fContactOwner" method="post" action="aContactOwner.php">' +
			'		<input type="hidden" name="toemail" value="' + email + '" />' +
			'		<input type="hidden" name="toname" value="<?php echo JSFriend2($name); ?>" />' +
			'		<input type="text" name="subject" placeholder="Subject" value="Inquiry About Sublet on SubLite" />' +
			'		<textarea name="message" placeholder="Message">' +
						'<?php echo JSFriend2($name); ?>,\r\n\r\nI am writing to inquire about your listing "<?php echo JSFriend2($l['title']); ?>" (http://sublite.net/listing.php?l=<?php echo $id; ?>) on SubLite.\r\n\r\nBest,\r\n<?php echo JSFriend2($myName); ?>' +
			'		</textarea>' +
			'		<div class="msg"></div>' +
			'		<div class="submit"><input class="button orange" type="submit" value="Send Message" /></div>' +
			'	</form>' +
			'</div>' +
			'<script>' +
			'	new Form(".fContactOwner .submit", "form.fContactOwner", ".fContactOwner .msg", function(data) {' +
			'		if (data.length == 0) {' +
			'			$(".modal").html("Your message has been sent to the owner!");' +
			'		}' +
			'	});' +
			'</sc' + 'ript>');
	}
</script>

<div class="fillcontent">
	<div class="listingleft" style="margin: 0 1em;">
		<div class="contentblock" style="width: 100%; margin: 1em auto;">
			<div class="listing">
				<h3><div class="title"></div></h3>
				<div class="imgs">
					<!--<div class="scroll scrollleft"></div>
					<div class="scroll scrollright"></div>-->
				</div>
				<div class="imgsnone">Photos will be available upon request.</div>
				<div class="location">
					<div class="address"></div>, <div class="city"></div>, <div class="state"></div>
				</div>
				<div class="price"></div>
				<div class="fb-like" data-href="https://sublite.net/listing.php?l=<?php echo $id; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" style="display: block; margin: 1em 0 2em 0;"></div>
				<?php
					if ($isSelf) {
						echo '
							<strong>This is your listing, be sure to share it on Facebook as well!</strong>
							<script>overlay("<center>This is your listing. Be sure to click Share to share the listing on Facebook as well!</center>"); $(".fb-like" ).clone().appendTo(".modal center");</script>';
					}
				?>
				<br />
				<h5>Room Type</h5>
				<div class="room"></div>
				<h5>Building Type</h5>
				<div class="building"></div>
				<h5>Maximum Occupancy</h5>
				<div class="occ"></div>
				<h5>Availability</h5>
				<div class="from"></div> to <div class="to"></div>
				<h5>Summary</h5>
				<div class="summary"></div>
				<?php
					if (count($l['amenities']) > 0)
						echo '<h5>Amenities</h5>';
					foreach ($l['amenities'] as $text) {
						echo '<check>' . $text . '</check>';
					}
				?>
			</div>
		</div>
		<div class="contentblock" style="width: 100%; margin: 1em auto; text-align: left;">
			<h3>Comments</h3>
			<div class="comments"></div>
			<script>
				function loadComments() {
					$('.comments').html('<img src="gfx/load.gif" />');
					$('.comments').load('fComment.php?l=<?php echo $id; ?>');
				}
				loadComments();
			</script>
		</div>
	</div>
	<div class="contentblock listingright">
		<div class="lister">
			<a class="profilelink">
				<h3><div class="name"></div></h3>
				<div class="pic"></div>
			</a>
			<div class="college"></div>
			<div class="school"></div>
			<div class="class"></div>
			<br />
			<?php
				if (!$isSelf) { 
					if (isset($_SESSION['email'])) {
			?>
						<div><input class="button orange" type="button" value="Contact Owner" /></div>
						<strong><a><div style="cursor: pointer;" class="email" onclick="sendInquiry(this);"></div></a></strong>
			<?php
					} else {
			?>
						<strong><a href="?<?php echo $_SERVER['QUERY_STRING']; ?>&login">You must login to contact the owner!</a></strong>
			<?php
					}
				}
			?>
			<br />
			<div class="bio"></div>
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