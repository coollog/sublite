<?php require_once('blockcss.php'); ?>
<a href="<?php echo "listing.php?l=$id"; ?>" target="_blank">
	<div class="block" style="background-image: url('<?php echo $img; ?>');">
		<div class="info">
			<div class="title"><?php echo $title; ?></div>
			<div class="data">
				<div class="proximity"><?php echo $proximity; ?>mi</div> &nbsp;
				<div class="price">$<?php echo $price; ?><smaller>/wk</smaller></div>
			</div>
			<div class="subtitle" style="display: block"><?php echo $subtitle; ?></div>
		</div>
	</div>
</a>