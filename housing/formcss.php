<?php require_once('header.php'); ?>
<?php require_once('htmlheader.php'); ?>
<style>
	.form {
		max-width: 20em;
		margin: 0 auto;
		text-align: center;
		font-size: 1.1em;
		line-height: 2em;
	}
	.slider {
		width: 80%;
		margin: 0.5em auto;
	}
	input {
		margin: 0.5em 0;
	}
	input[type=number], input[type=text], input[type=password], textarea, select {
		<?php cssCross('transition: all 0.30s ease-in-out;'); ?>
		outline: none;
		padding: 0.5em 0px 0.5em 0.5em;
		border: 1px solid #DDDDDD;
		<?php cssCross('border-radius: 4px;'); ?>
		width: 100%;
		font-size: 1em;
		<?php cssCross('box-sizing: border-box;'); ?>
	}
	input[type=number]:focus, input[type=text]:focus, input[type=password]:focus, textarea:focus, select:focus {
		box-shadow: 0 0 5px rgba(81, 203, 238, 1);
		border: 1px solid rgba(81, 203, 238, 1);
	}
	input[type=checkbox], input[type=radio] {
		display: inline-block;
		width: 1em;
		height: 1em;
		background: #fff;
		border: 1px solid #ccc;
		margin-right: 0.5em;
	}
	textarea {
		height: 10em;
		display: block;
		font: inherit;
	}
	.form input[type=submit] {
		width: 100%;
		height: 2em;
		<?php cssCross('box-sizing: border-box;'); ?>
	}
	.iframe {
		border: 1px solid #999;
		text-align: center;
	}
	iframe {
		border: 0;
		margin: 1em;
		width: 90%;
		display: inline-block;
		<?php cssCross('box-sizing: border-box;'); ?>
	}
	.msg {
		display: none;
		border: 1px solid;
		color: #D8000C;
		background: #FFBABA;
		cursor: pointer;
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
		line-height: 2em;
	}
	.msg:hover {
		opacity: 0.5;
	}
	
	optionbox {
		border: 1px solid #ccc;
		border-left: 0;
		border-right: 0;
		display: block;
		margin: 0.5em 0;
	}
		obtitle {
			margin: 0.5em 0;
			height: 2em;
			line-height: 2em;
			font-size: 1.1em;
			display: block;
			cursor: pointer;
			position: relative;
			font-weight: bold;
			<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
		}
		obtitle:before {
			background: url('gfx/down.png') no-repeat center center;
			background-size: contain;
			content: "";
			display: block;
			position: absolute;
			width: 1.5em;
			height: 1.5em;
			top: 0.25em;
			left: 1em;
		}
		obtitle:after {
			background: url('gfx/down.png') no-repeat center center;
			background-size: contain;
			content: "";
			display: block;
			position: absolute;
			width: 1.5em;
			height: 1.5em;
			top: 0.25em;
			right: 1em;
		}
		obtitle.obup:before {
			background-image: url('gfx/up.png');
		}
		obtitle.obup:after {
			background-image: url('gfx/up.png');
		}
		obtitle:hover {
			background: #eee;
		}
		oboptions {
			display: block;
			margin: 0 0 0.5em 0;
			padding: 0em 1em;
			text-align: left;
		}
</style>