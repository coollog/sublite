<?php
	$requireLogin = true;
	require_once('header.php');
	
	$limit = 5;
	$cursor = $L->cListings->find(array(
		'publish' => true,
	), array(
		'title' => true,
		'location' => true,
		'city' => true,
		'state' => true,
		'price' => true,
		'N' => true,
		'W' => true,
		'imgs' => array('$slice', 1)));
	// Limiter
	$size = $cursor->count();
	$cursor->sort(array('_id' => -1))->limit($limit);
	
	$geocode = geocode('Current Location');
	if ($geocode != null) {
		$N = $geocode['latitude'];
		$W = $geocode['longitude'];
	}
	foreach ($cursor as $doc) {		
		$id = $doc['_id'];
		if (isset($doc['imgs'][0])) {
			$img = https($doc['imgs'][0]);
		} else {
			$img = 'nopic.png';
		}
		$title = $doc['title'];
		$subtitle = $doc['location'] . ', ' . $doc['city'] . ', ' . $doc['state'];
		$price = $doc['price'];
		$proximity = 0;
		if ($geocode != null) {
			$dN = $doc['N'];
			$dW = $doc['W'];
			$proximity = round(distance($N, $W, $dN, $dW), 1);
		}
		require('iBlock.php');
	}
?>
<script>
	$('.block .proximity').hide();
</script>
<?php	
	require_once('footer.php');
?>