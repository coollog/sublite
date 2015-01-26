<?php require_once('header.php'); ?>
<style>
	.navbar {
		height: 3em;
		padding: 1.5em 4em 1em 4em;
		<?php //cssCross('box-shadow: 0px 0px 4px rgba(0,0,0,.4);'); ?>
		display: block;
		z-index: 10;
		position: relative;
	}
	.navbar .logo {
		height: 4em;
		margin-top: -1em;
		float: left;
	}
	.navbar a {
		height: 100%;
	}
	.navbar .right {
		float: right;
		height: 2em;
	}
	.navbar .icon {
		margin-left: 0.5em;
		cursor: pointer;
		height: 2em;
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
	}
	@media (min-width: 1000px) {
		.navbar .link {
			display: none;
		}
		.navbar .navoption {
			display: inline-block;
			width: auto;
			height: 100%;
			position: relative;
			margin-left: 0.5em;
			top: -0.5em;
			padding: 0 2em;
		}
	}
	@media (max-width: 1000px) {
		.navbar .navoption {
			display: none;
		}
	}
	.navbar .icon:hover {
		opacity: 0.5;
	}
</style>
<div class="navbar">
	<a href="."><img class="logo" src="gfx/logodaydarker.png" /></a>
	<div class="right">
		<a href="../jobs/search.php"><input class="button orange navoption" type="button" value="Search for Internships"></a>
		<a href="search.php"><img class="icon" src="gfx/msearch.png" /></a>
		<a href="account.php"><img class="icon" src="gfx/maccount.png" /></a>
		<a href="list.php"><input class="button orange navoption" type="button" value="Create Listing"><img class="icon link" src="gfx/madd.png" /></a>
	</div>
</div>