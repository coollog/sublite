<?php
	require_once('header.php');
	
	require_once('htmlheader.php');
	require_once('panelcss.php');
?>

<?php $page = 'Team'; require('menu.php'); ?>
<div class="panel" style="background: #fc9a50; color: #fff; padding: 2em 0; margin-bottom: -2em;">
	<div style="font-size: 1.4em; font-weight: 600;">Resources</div>
</div>
<style>
	.content .pic {
		display: inline-block;
		background: #fff no-repeat 5% center;
		background-size: 30%;
		min-height: 15em;
	}
	.content .bio {
		width: 60%;
		display: inline-block;
		padding: 2em;
		float: right;
	}
	.bio bigger {
		display: block;
		margin-bottom: 1em;
	}
</style>
<div class="panel" style="text-align: left; background: #fc9a50;">
	<div class="content">
		<a href="http://admitsee.com/" target="_blank"><div class="pic" style="color: #a14233; background-image: url('gfx/admitsee.png');">
			<div class="bio">
				<bigger>AdmitSee</bigger>
				
				AdmitSee is a social media platform where thousands of verified undergrad and grad students share their application materials, including personal statements, test scores, extracurricular resumes, and high school background. The information is uploaded to profiles (similar to LinkedIn/Facebook), so it&rsquo;s fun and easy for prospective students to navigate.
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div></a>
	</div>
	<div class="content" style="margin-top: 4em;">
		<a href="http://veryapt.com/" target="_blank"><div class="pic" style="color: #a14233; background-image: url('gfx/veryapt.png');">
			<div class="bio">
				<bigger>VeryApt</bigger>
				
				VeryApt is a trusted apartment review community for people who want to make sure their next apartment really feels like home.
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div></a>
	</div>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>
