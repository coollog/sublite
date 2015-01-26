<style>
	.block {
		<?php cssCross('box-shadow: 0px 0px 5px rgba(0,0,0,.2);'); ?>
		<?php cssCross('box-sizing: border-box;'); ?>
		width: 50em;
		max-width: 100%;
		height: 20em;
		display: inline-block;
		background: #fff no-repeat center center;
		background-size: cover;
		position: relative;
		text-align: left;
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
		cursor: pointer;
		margin: 0.5em 0;
		color: #111;
	}
	.block:hover {
		opacity: 0.7;
	}
	.block .title {
		display: inline-block;
		font-size: 1.4em;
		
	}
</style>