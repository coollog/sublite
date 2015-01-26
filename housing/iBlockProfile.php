<?php require_once('blockprofilecss.php'); ?>
<a href="<?php echo "$link?l=$id"; ?>">
	<div class="block" style="background-image: url('<?php echo $img; ?>');">
		<div class="info">
			<div class="title"><?php echo $title; ?></div> 
			<div class="price">$<?php echo $price; ?>/week</div>
		</div>
	</div>
</a>