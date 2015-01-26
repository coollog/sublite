<?php require_once('formcss.php'); ?>
<style>
	body {
		background: #fef9f9;
	}
	.panel {
		padding: 4em 0;
		background: #fef9f9 no-repeat bottom center;
		background-size: cover;
		text-align: center;
		<?php cssCross('box-sizing: border-box;'); ?>
		<?php cssCross('transition: 3s background-image ease-in-out;'); ?>
	}
	.panel h1 {
		font-size: 1.8em;
		font-weight: bolder;
		margin: 2em 0;
		line-height: 1.1em;
		letter-spacing: 1px;
	}
	.panel .horange {
		padding: 0.5em 0;
		margin: 1em 0;
		background: #f78d1d;
		color: #fff;
		text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4);
	}
	.panel h2 {
		font-size: 1.8em;
		font-weight: bolder;
		margin: 0em 0 1.5em 0;
		line-height: 1.1em;
		letter-spacing: 1px;
	}
	.panel h3 {
		font-size: 1.3em;
		font-weight: normal;
		margin: 2em 0 0.5em 0;
		line-height: 1.1em;
		letter-spacing: 0.5px;
		text-align: center;
	}
	.panel h5 {
		font-size: 1.1em;
		font-weight: normal;
		margin: 1em 0 0.5em 0;
		line-height: 1.3em;
		letter-spacing: 0.5px;
	}
	.panel .content {
		max-width: 60em;
		margin: 0 auto;
	}
	.panel .logo {
		display: inline-block;
		width: 100%;
		height: 8em;
		background: url('gfx/logodaydarker.png') no-repeat center center;
		background-size: contain;
	}
	.panel .email .extension {
		display: inline;
	}
	.hidden {
		display: none;
	}
</style>