<?php
	require_once('header.php');
	
	if (isset($_SESSION['email'])) {
		header("Location: http://$domain/search.php");
		exit();
	}
	
	$users = $E->cEmails->find();
	$usercount = $users->count();
	$schoolscount = count($S->LUT);
	$listings = $L->cListings->find();
	$listingscount = $listings->count();
	$citycount = 24;
	
	require_once('htmlheader.php');
	require_once('panelcss.php');
?>

<style>
	.ea {
		display: inline-block;
	}
	.split .block {
		width: 33%;
		<?php cssCross('box-sizing: border-box;'); ?>
		min-width: 10em;
		display: inline-block;
		padding: 1em;
		text-align: left;
		vertical-align: top;
	}
</style>
<script>
	var imgs = ['gfx/front/barcelona.jpg', 'gfx/front/warsaw.jpg', 'gfx/front/cityscape.jpg', 'gfx/front/tokyo.jpg'];
	preloadImages(imgs);

	$(function() {
		
		buttonset('.selschool');
		
		var emailHTML;
		function setupEmail(msg) {
			$('.login').hide();
			$('.email .submit').show().html(emailHTML);
			if (msg.length > 0)
				$('.email .msg').show().html(msg);
			$("input[name='pass']").focus();
			
			var school = $("input[name='school']:checked").val();
			$("input[name='email']").focus();
			$('.extension').html(school);
			$("input[name='extension']").val(school);
		}
		var confirm = false;
		$('form.fVerify').submit(function() {
			emailHTML = $('.email .submit').html();
			$("input[name='email']").val($("input[name='email']").val().toLowerCase() + domain);
			loadEmail($(this).serializeObject());
			$('.email .submit').hide();
			$('.email .loading').html(loadgif).show();
			return false;
		});
		function loadEmail(postData) {
			$.post('aVerify.php', postData).done(function(data) {console.log(data);
				switch (data) {
					case '-2': // Email invalid
						setupEmail('Invalid email address!');
						break;
					case '-1': // Not .edu
						setupEmail('You must use a .edu email!<br />If your school\'s extension is not .edu, send us an email at <a href="mailto: info@sublite.net">info@sublite.net</a>.');
						break;
					case '0': // All good
						var email = postData['email'];
						$.post('aHasAccount.php', {email: email}).done(function(data) {
							switch (data) {
								case '0': // Has account: show login form
									$('.email').hide();
									$(".login input[name='email']").val(email);
									$('.login .ea').html(email);
									function setupLogin() {
										$('.login').show();
										$(".login input[name='pass']").focus();
										
										var loginHTML;
										$('form.fLogin').submit(function() {
											loginHTML = $('.login').html();
											$('.login .submit').html(loadgif);
											
											var postData = $(this).serializeObject();
											$.post('aLogin.php', postData).done(function(data) {
												console.log(data.length);
												if (data.length > 0) {
													$('.login').html(loginHTML);
													$('.login .msg').show().html(data);
													setupLogin();
												} else {
													window.location.href = 'aAfterLogin.php';
												}
											});
											return false;
										});
									}
									setupLogin();
									break;
								case '-1': // Confirm email
									$('.email').hide();
									$('.preconfirm .address').html(email);
									$('.preconfirm').show().attr('email', email);
									break;
							}
						});
						break;
					default: // Show initial form
						setupEmail('');
				}
			});
		}
		loadEmail();
		$('.nothere').click(function() {
			overlay('<h1>Get Your School to Join!</h1>Let us know what school you go to and we\'ll contact your administration if we receive enough demand.');
		});
	});
</script>

<?php $page = 'About'; require('menu.php'); ?>
<div class="panel" style="background: url('gfx/front/ss1.jpg') no-repeat center 20%; background-size: cover; color: #fff; min-height: 75%;">
	<div class="content">
		<div style="font-size: 1.2em; max-width: 30em; margin: 0 auto;">
			<div style="background: rgba(0, 0, 0, 0.3); box-shadow: 0 0 20px rgba(0, 0, 0, 0.6); padding: 2em 1em; border-radius: 1em;">
				<b>A Student-to-Student Subletting Network</b>
				<br /><br />
				List and share your property or search for the perfect summer sublet. Verify your &ldquo;.edu&rdquo; email address to get started! It&rsquo;s completely free!
			</div>
			<br /><br />
			<div class="form" style="font-size: 1em; width: 18em;">
				<?php
					if ($_SESSION['afterLogin'] != $defaultAfterLogin) {
						echo "<h5 style=\"color: #a14233;\">You must login!</h5>";
					}
				?>
				<div class="email" style="text-shadow: 0 0 4px rgba(0, 0, 0, 0.7);">
					<b class="prompt">What's your .edu email address?</b>
					<div class="msg"></div>
					<form method="post" class="fVerify">
						<script>
							var domain = '';
						</script>
						<?php
							function schoolOf($sid) {
								switch ($sid) {
									case 'hr3dkd':
										return 'rutgers.edu'; break;
									case '3hfiem':
										return 'pitt.edu'; break;
									case 'd94jg3':
										return 'utexas.edu'; break;
									case '32ka9j':
										return 'nd.edu'; break;
									default:
										die('<script>window.location.href="./";</script>');
								}
							}
							if (isset($_REQUEST['sid'])) {
								$sid = $_REQUEST['sid'];
						?>
							<strong>Welcome <?php echo $S->LUT[schoolOf($sid)]; ?>!</strong><br />
							<input type="text" name="email" style="width: <?php echo 80-3*strlen(schoolOf($sid)); ?>%;" /> @<?php echo schoolOf($sid); ?>
							<script>domain = "@<?php echo schoolOf($sid); ?>";</script>
						<?php
							} else {
						?>
							<input type="text" name="email" />
						<?php
							}
						?>
						<br /><div class="submit"><input class="button orangenew" type="submit" value="Verify or Log In" /></div>
						<div class="loading hidden"></div>
					</form>
				</div>
				<div class="login hidden" style="color: #a14233;">
					<h3>Login</h3>
					<div class="msg"></div>
					<form method="post" class="fLogin">
						<div class="ea"></div>
						<input type="hidden" name="email" />
						
						<input type="password" name="pass" placeholder="Password" />
						<div class="submit"><input class="button orangenew" type="submit" value="Login" /></div>
					</form>
				</div>
				<div class="preconfirm hidden">
					<b style="color: #a14233;">Please verify that &ldquo;<span class="address"></span>&rdquo; is correct.</b><br />
					<input class="go button orangenew" type="button" value="Good to go!" />
					<input class="no button orangenew" type="button" value="Go back." />
					<script>
						$('.preconfirm .go').click(function() {
							$('.preconfirm').html(loadgif);
							$.post('aSendConfirm.php', {email: $('.preconfirm').attr('email')}).done(function(data) {console.log(data);
								$('.preconfirm').hide();
								$('.confirm .ea').html(data);
								$('.confirm').show();
							});
						});
						$('.preconfirm .no').click(function() {
							$('.email').show();
							$('.email .submit').show();
							$('.email .loading').hide();
							$('.preconfirm').hide();
						});
					</script>
				</div>
				<div class="confirm hidden" style="color: #a14233"">
					A confirmation email has been sent to <div class="ea"></div>. Check your inbox or spam. The email may take up to 24 hours to show up.
					<br /><br />
					<span style="color: #fc9a50;">Like us on Facebook!</span>
					<div class="fb-like-box" style="display: block; margin-left: 40px;" data-href="https://www.facebook.com/SubLiteNet" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<anchor name="about"></anchor><div class="panel" style="background: #fff; color: #fc9a50; padding: 2em 0;">
	<div class="content">
		<style>
			.counts {
				width: 100%;
				font-size: 1.3em;
				font-weight: 600;
			}
			.counts td {
				width: 25%;
			}
		</style>
		<table class="counts"><tr>
			<td><bigger><?php echo number_format($usercount); ?></bigger> users</td>
			<td><bigger><?php echo number_format($schoolscount); ?></bigger> universities</td>
			<td><bigger><?php echo number_format($listingscount); ?></bigger> listings</td>
			<td><bigger><?php echo number_format($citycount); ?></bigger> cities</td>
		</tr></table>
	</div>
</div>
<style>
	h {
		color: #fedfc7;
		font-size: 1.2em;
		font-weight: 600;
		display: block;
		margin-bottom: 1em;
		text-align: center;
	}
</style>
<div class="panel" style="background: #fc9a50; color: #a14233;">
	<div class="content">
		<h>Why SubLite</h>
		<big style="color: #fff; line-height: 1.5em;">
			We know you&rsquo;ve worked hard to network and get top grades for your dream summer job &hellip; but that&rsquo;s only half the journey.
		</big>
		<br /><br />
		<div style="width: 70%; margin: 0 auto; text-align: left; letter-spacing: -0.5px;">
			Now you need to find your perfect summer accommodations so you can sip cocktails on your rooftop under the moonlight after work. Here is why SubLite provides the perfect solution for <i>you</i>:
		</div>
		<div class="split">
			<style>
				.block img {
					height: 7em;
					margin: 1em 0;
				}
			</style>
			<div class="block">
				<center><img src="gfx/verified.png"></center>
				<h>Verified</h>
				<div style=" letter-spacing: -0.5px;">
					SubLite is an exclusive student-to-student platform for listing and discovering sublets to complement summer internships.
					<br /><br />
					Unlike other housing platforms, all our users are verified using their school email addresses. Say goodbye to horror housing stories!
				</div>
			</div>
			<div class="block">
				<center><img src="gfx/efficient.png"></center>
				<h>Efficient</h>
				<div style=" letter-spacing: -0.5px;">
					No more browsing through countless Facebook posts and email threads - SubLite consolidates all of that information into a <i>single</i> platform. Find your ideal summer housing within minutes!
				</div>
			</div>
			<div class="block">
				<center><img src="gfx/relevant.png"></center>
				<h>Relevant</h>
				<div style=" letter-spacing: -0.5px;">
					We know exactly what you want. Our enhanced search functionality captures the essence of an ideal sublet for university students: 
					<ul>
						<li>Proximity to workplace</li>
						<li>Cost per week</li>
						<li>Occupancy</li>
						<li>Privacy</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="panel" style="background: #fff; color: #fc9a50; padding: 2em 0;">
	<style>
		.backed {
			position: relative;
			<?php cssCross('box-sizing: border-box;'); ?>
		}
		.backed big {
			display: block;
			font-style: italic;
		}
		.backedlogo {
			background: transparent no-repeat center center;
			background-size: auto 70%;
			height: 136px;
			width: 40%;
			position: absolute;
			top: 0;
		}
	</style>
	<div class="content backed" style="font-size: 1.2em; font-weight: 600; line-height: 1.8em;">
		<div class="backedlogo" style="background-image: url('gfx/ulogos/yale.png');"></div>
		SubLite is backed by the <big>Yale Venture Creation Program</big> and the <big>Wharton Innovation Fund.</big>
		<div class="backedlogo" style="background-image: url('gfx/ulogos/upenn.png'); right: 0;"></div>
	</div>
</div>
<div class="panel" style="background: #fc9a50; color: #fff;">
	<div class="content">
		<h>Our Network</h>
		<div style="color: #a14233;">SubLite is in <?php echo $citycount; ?> cities and still growing! Here is a list of our locations:</div>
		<br /><br />
		<style>
			.citylist small {
				opacity: 0.6;
				font-size: 0.7em;
			}
		</style>
		<big class="citylist" style="color: #fff; line-height: 1.5em; width: 70%; margin: 0 auto;">
			<small>Austin</small> &bull;
			<small>Berkeley</small> &bull; 
			<small>Bethesda</small> &bull; 
			Boston &bull; 
			<small>Brookline</small> &bull; 
			<small>Bryn Mawr</small> &bull; 
			<small>Buffalo</small> &bull; 
			<small>Cambridge</small> &bull; 
			<small>Champaign</small> &bull;
			Chicago &bull; 
			<small>College Station</small> &bull;
			<small>Davis</small> &bull;
			<small>Denver</small> &bull;
			<small>Evanston</small> &bull; 
			<small>Ithaca</small> &bull;
			Los Angeles &bull; 
			<small>Madison</small> &bull; 
			<small>New Haven</small> &bull;
			New York &bull;
			Philadelphia &bull;
			San Francisco &bull;
			<small>Santa Clara</small> &bull;
			<small>Toronto</small> &bull;
			Washington D.C. &bull;
		</big>
	</div>
</div>

<div class="panel" style="background: #fff; color: #fc9a50;">
	<div class="content">
		<h style="color: inherit;">What People Are Saying</h>
		<style>
			quote {
				color: #891300;
				font-family: 'PT Serif', serif;
				font-size: 1.6em;
				display: block;
				line-height: 1.5em;
			}
			quoter {
				text-transform: uppercase;
				display: block;
				margin: 1em;
				font-weight: 600;
			}
			school {
				opacity: 0.5;
			}
		</style>
		<quote>&ldquo;I was able to find two sublets easily and on really short notice, and everything's been smooth so far.&rdquo;</quote>
		<quoter>Lucy W. <school>&ndash; Yale University</school></quoter>
		<quote>&ldquo;I am blown away by how SubLite has become so useful for me. It definitely gives me peace of mind when I need a place to stay for an internship and what not!&rdquo;</quote>
		<quoter>Abdi M. <school>&ndash; St. Olaf College</school></quoter>
		<!--<quote>&ldquo;Without SubLite, I couldn&rsquo;t have subletted my apartment and found a sublet in LA during exams. With SubLite I did both in an afternoon.&rdquo;</quote>
		<quoter>Andrew <school>&ndash; University of Maryland</school></quoter>-->
	</div>
</div>
<center><small><i>Design by Design at Yale.</i></small></center>
<br />

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>