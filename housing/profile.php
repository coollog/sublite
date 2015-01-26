<?php
	$requireLogin = true;//die('<meta property="og:title" content="'.$_SERVER['REMOTE_ADDR'].'" />');
	require_once('header.php');	
	require_once('htmlheader.php');
	
	if (!isset($_GET['p'])) {
		echo 'Invalid ID!';
	} else {
		$id = $_GET['p'];
		if (!($p = $E->getProfile($id))) {
			echo "User doesn't exist!";
		} else {
			$email = $p['email'];
			$name = $p['name'];
			$pic = 'noprofilepic.png';
			if (isset($p['pic'])) {
				$pic = $p['pic'];
				if ($pic == 'nopic.png') $pic = 'noprofilepic.png';
			}
			$school = $S->nameOf($email);
			$bio = 'Welcome to my profile!';
			if (isset($p['bio']))
				$bio = $p['bio'];
			$class = '';
			if ($p['class'] > 0)
				$class = "Class of " . $p['class'];
			$school = 'Undergraduate';
			if (strlen($p['school']) > 0) {
				$school = $p['school'];
			}
			$listings = $L->search(array("email" => $email, "publish" => true));
?>

<?php require_once('navbar.php'); ?>

<?php require_once('contentcss.php'); ?>
<style>
	.pic {
		background-image: url('<?php echo $pic; ?>');
		width: 250px;
		height: 250px;
		background-size: contain;
		display: inline-block;
	}
	.info .email {
		display: none;
		word-wrap: break-word;
	}
	
	.modal iframe {
		border: 1px solid #ccc;
		margin: 0;
		width: 100%;
		display: block;
		height: 20em;
	}
	.accept {
		width: 100%;
		margin: 0.5em 0;
	}
</style>
<script>
	function setupTerms() {
		$('.accept').click(function() {
			$('.overlay').click();
			$('.info .email').show();
			$('.info input[type=button]').parent().hide();
		});
	}
	$(function() {
		$('.info input[type=button]').click(function() {
			overlay('<iframe src="terms.html"></iframe><br />I have read, fully understand, and agree to Sublite&rsquo;s Terms of Service. I agree to contact the host in good-faith to inquire about the listing.<br /><input class="button orange accept" type="button" value="Accept" /><script>setupTerms();</sc' + 'ript>');
		});
	});
</script>
<div class="fillcontent">
	<div class="contentblock profileleft">
		<h3><div class="name"><?php echo $name; ?></div></h3>
		<div class="pic"></div>
		<br />
		<div class="info">
			<div class="college"><?php echo $S->nameOf($email); ?></div>
			<div class="school"><?php echo $school; ?></div>
			<div class="class"><?php echo $class; ?></div>
			<br />
			<div><input class="button orange" type="button" value="View Email" /><br /></div>
			<strong><div class="email"><?php echo $email; ?></div></strong>
			<br />
			<div class="bio"><?php echo $bio; ?></div>
			<div class="fb-like" data-href="http://sublite.net/profile.php?p=<?php echo $id; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" style="margin-top: 2em;"></div>
		</div>
	</div>
	<div class="contentblock profileright">
		<h3>Listings</h3>
		<div class="listings">
			<?php echo $name; ?> does not seem to have any listings.
			<?php
				if ($listings->count() > 0) {
					echo "<script>$('.listings').html('');</script>";
					$link = 'listing.php';
					foreach ($listings as $l) {
						$id = $l['_id'];
						$img = $l['title'];
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
		}
	}
	
	require_once('htmlfooter.php'); 
    require_once('footer.php'); 
?>