<style>
	@import url(//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,800,600|PT+Serif);
	body, html {
		height: 100%;
		margin: 0;
		font-family: 'Open Sans', sans-serif;
		font-weight: 400;
		line-height: 1.5em;
	}

	a {
		color: #23DBC6;
		text-decoration: none;
	}
	a:active {
		color: #21A394;
	}
	a:hover {
		text-decoration: underline;
	}
	a:visited {
		color: #23DBC6;
	}
	b, strong {
		font-weight: bold;
	}
	small {
		font-size: 0.9em;
		opacity: 0.5;
	}
	smaller {
		font-size: 0.5em;
		opacity: 0.5;
	}
	center {
		text-align: center;
	}
	big {
		font-size: 1.6em;
	}
	bigger {
		font-size: 1.8em;
		font-weight: 700;
	}
	
	.buttonset input[type=button] {

	}
	.buttonset-sel {
		font-weight: bold;
	}

	.button {
		display: inline-block;
		outline: none;
		cursor: pointer;
		text-align: center;
		text-decoration: none;
		font-size: 1em;
		line-height: 2em;
		padding: 0em 2em;
		height: 2.5em;
		text-shadow: 0 1px 1px rgba(0,0,0,.3);
		<?php cssCross('border-radius: .5em;'); ?>
		<?php cssCross('box-shadow: 0 1px 2px rgba(0,0,0,.2);'); ?>
		<?php cssCross('transition: all 0.1s ease-in-out;'); ?>
	}
	.button:hover {
		text-decoration: none;
	}
	.button:active {
		position: relative;
		top: 1px;
	}
		.button.orange {
			color: #fef4e9;
			border: solid 1px #da7c0c;
			background: #f78d1d;
			background: -webkit-gradient(linear, left top, left bottom, from(#faa51a), to(#f47a20));
			background: -moz-linear-gradient(top,  #faa51a,  #f47a20);
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#faa51a', endColorstr='#f47a20');
		}
		.button.orange:hover {
			background: #f47c20;
			background: -webkit-gradient(linear, left top, left bottom, from(#f88e11), to(#f06015));
			background: -moz-linear-gradient(top,  #f88e11,  #f06015);
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#f88e11', endColorstr='#f06015');
		}
		.button.orange:active {
			color: #fcd3a5;
			background: -webkit-gradient(linear, left top, left bottom, from(#f47a20), to(#faa51a));
			background: -moz-linear-gradient(top,  #f47a20,  #faa51a);
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#f47a20', endColorstr='#faa51a');
		}
		.button.orangenew {
			color: #fff;
			<?php cssCross('border-radius: 1.25em;'); ?>
			background: #fdae73;
			text-shadow: none;
			<?php cssCross('box-shadow: none;'); ?>
			border: 0;
		}
		.button.orangenew:hover {
			background: #ff8c36;
		}
		.button.orangenew:active {
		}
		.button.green {
			color: #ffffff;
			border:1px solid #578211;
			background-color:#8DBF53;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8DBF53), color-stop(1, #3EB22F) );
			background:-moz-linear-gradient( center top, #8DBF53 5%, #3EB22F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8DBF53', endColorstr='#3EB22F');
		}
		.button.green:hover {
			background-color:#3EB22F;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #3EB22F), color-stop(1, #8DBF53) );
			background:-moz-linear-gradient( center top, #3EB22F 5%, #8DBF53 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3EB22F', endColorstr='#8DBF53');
		}
		.button.green:active {		
			background: #6F9641;
			background: -webkit-gradient(linear, left top, left bottom, from(#6F9641), to(#2F8A25));
			background: -moz-linear-gradient(top,  #6F9641,  #2F8A25);
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#6F9641', endColorstr='#2F8A25');
		}
		.button.gray {
			background-color:#f9f9f9;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
			background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');
			border:1px solid #dcdcdc;
		}
		.gray:hover {
			background-color:#e9e9e9;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9) );
			background:-moz-linear-gradient( center top, #e9e9e9 5%, #f9f9f9 100% );			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9');
		}
		.gray:active {
			background-color:#f9f9f9;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
			background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');
		}

	.overlay {
		position: fixed;
		left: 0; top: 0; right: 0; bottom: 0;
		background: rgba(0, 0, 0, 0.8);
		z-index: 100;
		<?php cssCross('transition: 0.5s all ease-in-out;'); ?>
		opacity: 0;
		width: 100%;
		height: 100%;
	}
	.modal {
		margin-left: -100%; margin-top: -100%;
		position: fixed;
		left: 50%; top: 50%;
		z-index: 200;
		background: #fff;
		padding: 4em;
		max-width: 90%;
		max-height: 90%;
		<?php cssCross('box-shadow: 4px 4px 2px rgba(0,0,0,.4);'); ?>
		<?php cssCross('box-sizing: border-box;'); ?>
		<?php cssCross('transition: 0.5s all ease-in-out;'); ?>
		opacity: 0;
		max-height: 100%;
		overflow: auto;
		-webkit-overflow-scrolling: touch;
	}
	.modal h1 {
		font-size: 1.8em;
		font-weight: bolder;
		margin: 0.5em 0 1em 0;
		line-height: 1.1em;
		letter-spacing: 1px;
		text-align: center;
	}
	.footertext iframe {
		border: 1px solid #ccc;
		margin: 0;
		width: 100%;
		display: block;
		height: 50em;
	}
	
	check {
		display: block;
	}
	check:before {
		display: inline-block;
		content: "";
		width: 1em;
		height: 1em;
		background: url('gfx/check.png') no-repeat center center;
		background-size: contain;
		margin-right: 0.5em;
	}
</style>