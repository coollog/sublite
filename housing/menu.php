<div class="panel" style="width: 100%; background: #fc9a50; color: #fff; min-height: 5em; padding: 0.5em 1em;">
	<a href="."><img src="gfx/logoday.png" style="float: left; height: 3em; margin: 0.5em 1em" /></a>
	<style>
		.links {
			line-height: 4em;
		}
		.links a {
			opacity: 0.5;
			<?php cssCross('transition: all 0.1s ease-in-out;'); ?>
			font-size: 1.5em;
			margin: 0 0.5em;
			cursor: pointer;
			font-weight: 600;
			color: #fff;
		}
		.links a:hover, .links .here {
			text-decoration: underline;
			opacity: 1;
		}
		.links img {
			height: 1em;
		}
	</style>
	<div class="links" style="float: right;">
		<a <?php if ($page == 'About') echo 'class="here hashchange" href="#about"'; else echo 'class="hashchange" href="."'; ?>>About</a>
		<a href="team.php" <?php if ($page == 'Team') echo 'class="here"'; ?>>Team</a>
		<a href="resources.php">Resources</a>
		<a href="faq.php" <?php if ($page == 'FAQ') echo 'class="here"'; ?>>FAQ</a>
		<a href="http://sublitenews.blogspot.com/" target="_blank"><img src="gfx/blogger.png"></a>
		<a href="https://twitter.com/SubLiteNet" target="_blank"><img src="gfx/twitter.png"></a>
		<a href="https://www.facebook.com/SubLiteNet" target="_blank"><img src="gfx/fb.png"></a>
	</div>
    <div class="spacer" style="clear: both;"></div>
</div>