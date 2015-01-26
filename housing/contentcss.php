<?php require_once('formcss.php'); ?>
<style>
	body {
		background: #fafafa;
	}
	.fillcontent {
		text-align: center;
		background: #fafafa;
		min-height: 90%;
	}
	.fillcontent h1 {
		font-size: 1.8em;
		font-weight: bolder;
		margin: 1.5em 0;
		line-height: 1.1em;
		letter-spacing: 1px;
	}
	.fillcontent h3 {
		font-size: 1.3em;
		font-weight: normal;
		margin: 1.5em 0;
		line-height: 1.1em;
		letter-spacing: 0.5px;
	}
	.fillcontent h5 {
		font-size: 1.1em;
		font-weight: normal;
		margin: 1em 0;
		line-height: 1.3em;
		letter-spacing: 0.5px;
	}
	.contentblock {
		display: inline-block;
		margin: 1em;
		background: #fff;
		<?php cssCross('border-radius: 1em;'); ?>
		<?php cssCross('box-shadow: 1px 4px 10px rgba(0,0,0,.2);'); ?>
		vertical-align: top;
		<?php cssCross('box-sizing: border-box;'); ?>
		padding: 2em;
		<?php cssCross('transition: 0.5s all ease-in-out;'); ?>
	}
	@media (min-width: 1100px) {
		.profileleft {
			min-width: 200px;
			width: 30%;
			max-width: 100%;
		}
		.profiletop {
			max-width: 100%;
		}
		.profileright {
			min-width: 600px;
			width: 60%;
			max-width: 100%;
		}
		.messagetop {
			min-width: 600px;
			max-width: 100%;
		}
		.messagebottom {
			min-width: 600px;
			max-width: 100%;
		}
		.searchleft {
			min-width: 150px;
			/*width: 30%;*/
			width: 90%;
			max-width: 100%;
		}
		.searchleft-left {
			width: 30%;
		}
		.searchright {
			min-width: 650px;
			/*width: 60%;*/
			width: 60%;
			max-width: 100%;
		}
		.searchright-right {
			width: 60%;
		}
		.listingleft {
			min-width: 650px;
			width: 65%;
			max-width: 100%;
			display: inline-block;
			<?php cssCross('box-sizing: border-box;'); ?>
		}
		.listingright {
			min-width: 150px;
			width: 25%;
			max-width: 100%;
			display: inline-block;
			<?php cssCross('box-sizing: border-box;'); ?>
		}
	}
	@media (max-width: 1100px) {
		.contentblock {
			width: 90%;
		}
	}
	.listings {
		text-align: center;
	}
</style>