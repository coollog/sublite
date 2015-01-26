<?php
	$requireLogin = true;
	require_once('header.php');	
	require_once('htmlheader.php');
?>

<?php require_once('navbar.php'); ?>

<?php require_once('contentcss.php'); ?>
<style>
</style>
<script>
	$(function() {			
		
	});
</script>

<div class="fillcontent">
	<div class="contentblock">
		<a href="search.php"><input class="button orange bSearch" type="button" value="Search for Sublets" /></a>
		<br /><br />
		<a href="list.php"><input class="button orange bList" type="button" value="Create a Listing" /></a>
	</div>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>