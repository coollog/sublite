<?php
	$requireLogin = true;
	require_once('header.php');
	
	$location = $_POST['location'];
	$geocode = geocode($location);
	$error = '';
	if ($geocode == null) {
		$error = 'Invalid location!';
	} else {
		$N = $geocode['latitude'];
		$W = $geocode['longitude'];
		$from = strtotime($_POST['from']);
		$to = strtotime($_POST['to']);
		$maxProximity = (int)$_POST['proximity'];
		$minPrice = (float)$_POST['price0'];
		$maxPrice = (float)$_POST['price1'];
		$minOccupancy = (int)$_POST['occ'];
		if (isset($_POST['amenities']))
			$amenities = $_POST['amenities'];
		else 
			$amenities = array();
		$room = $_POST['room'];
		$building = $_POST['building'];
		$sortby = $_POST['sort'];
		
		$skip = (float)$_POST['skip'];
		$limit = 100;
		
		if ($maxProximity > 50 || 
			$minPrice < 0 || $minPrice > $maxPrice || $maxPrice > 2000 ||
			$minOccupancy < 1 || $minOccupancy > 10) {
			$error = 'Invalid filters!';
		} else {
			// Query for data
			$findquery = array(
				'publish' => true,
				'price' => array('$gte' => $minPrice, '$lte' => $maxPrice),
				'occ' => array('$gte' => $minOccupancy)
			);
			if ($room != 'Any') {
				$findquery['room'] = $room;
			}
			if ($building != 'Any') {
				$findquery['building'] = $building;
			}
			if (count($amenities) > 0) {
				$findquery['amenities'] = array('$in' => $amenities);
			}
			if (strlen($from) > 0) {
				$findquery['from'] = array('$lte' => $from);
			}
			if (strlen($to) > 0) {
				$findquery['to'] = array('$gte' => $to);
			}
			$starttime = microtime(true);
			$cursor = $L->cListings->find($findquery, array(
				'title' => true,
				'price' => true,
				'location' => true,
				'city' => true,
				'state' => true,
				'N' => true,
				'W' => true,
				'imgs' => array('$slice', 1)));
			// Limiter
			$size = $cursor->count(); $more = ($skip + $limit < $size);
			//$cursor->skip($skip)->limit($limit);
			
			$delay = microtime(true) - $starttime;
			// Sort
			switch ($sortby) { // Sorts for cursor
				case 'priceIncreasing':
					$cursor->sort(array('price' => 1));
					break;
				case 'priceDecreasing':
					$cursor->sort(array('price' => -1));
					break;
			}
			$docs = array();
			foreach ($cursor as $doc) {
				$dN = $doc['N'];
				$dW = $doc['W'];
				$doc['proximity'] = distance($N, $W, $dN, $dW);
				if ($doc['proximity'] <= $maxProximity)
					$docs[] = $doc;
			}
			switch ($sortby) { // Sorts for array
				case 'proximityIncreasing':
					function sorter($a, $b) {
						if ($a['proximity'] < $b['proximity']) {
							return -1;
						} else if ($a['proximity'] > $b['proximity']) {
							return 1;
						} else {
							return 0; 
						}
					}
					usort($docs, 'sorter');
					break;
			}
			echo "(" . count($docs) . " results found in " . number_format($delay, 5) . " seconds.)<br />";
			foreach ($docs as $doc) {
				$id = $doc['_id'];
				if (isset($doc['imgs'][0])) {
					$img = https($doc['imgs'][0]);
				} else {
					$img = 'nopic.png';
				}
				$title = $doc['title'];
				$subtitle = $doc['location'] . ', ' . $doc['city'] . ', ' . $doc['state'];
				$price = $doc['price'];
				$proximity = round($doc['proximity'], 1);
				require('iBlock.php');
			}
			if (count($docs) == 0) {
				if ($size == 0) {
					echo "No results found for your search. Try increasing ranges.";
				} else {
					echo "No listings near your area. Contact us at <a href='mailto: info@sublite.net'>info@sublite.net</a> and we'll reach out to that area!";
				}
			} else
				echo "<script>showSort();</script>";
			
			if ($more) {
				echo "<script>$('input[name=loadMore]').show();</script>";
			}
		}		
	}
	
	if (strlen($error) > 0) {
		echo json_encode(array('error' => $error));
	}
	
	require_once('footer.php');
?>