<?php
	$requireLogin = true;
	require_once('header.php');
	
	// For editing
	if (isset($_POST['l'])) {
		$id = $_POST['l'];
		$l = $L->get($id);
		
		if ($l['email'] == $_SESSION['email']) {
			// Publishing
			if (isset($_POST['publish'])) {
				$L->publish($id, true);
			}
			if (isset($_POST['unpublish'])) {
				$L->publish($id, false);
			}
			// Deleting
			if (isset($_POST['del'])) {
				//require_once('sendmail.php');
				require_once('sendgmail.php');
				$message = json_encode($L->get($id));
				//sendmail('info@sublite.net', 'SubLite Listing DELETION', $message, '"SubLite, LLC." <info@sublite.net>');
				sendgmail(array('info@sublite.net', 'SubLite, LLC.'), array('info@sublite.net', 'SubLite, LLC.'), 'SubLite Listing DELETION', $message);
				$L->remove($id);
			}
		}
	}
	if (!isset($_POST['publish']) and !isset($_POST['unpublish']) and
		!isset($_POST['del'])) {
		
		if (!isset($_POST['tos'])) {
			echo 'You must agree to the Terms of Service!';
		} else {
			$location = $_POST['location'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$address = "$location, $city, $state";
			$geocode = geocode($address);
			if ($geocode == null) {
				echo 'Invalid location!';
			} else {
				$location = htmlentities($location);
				$city = htmlentities($city);
				$state = htmlentities($state);
				$N = $geocode['latitude'];
				$W = $geocode['longitude'];
				$from = strtotime($_POST['from']);
				$to = strtotime($_POST['to']);
				$price = (float)$_POST['price'];
				$title = htmlentities($_POST['title']);
				$summary = utf8_encode(nl2br(htmlentities($_POST['summary'])));
				$occ = (float)$_POST['occ'];
				$room = htmlentities($_POST['room']);
				$building = htmlentities($_POST['building']);
				$imgs = array();
				if (isset($_POST['img'])) {
					foreach ($_POST['img'] as $img) {
						$imgs[] = htmlentities($img);
					}
				}
				
				if (strlen($from) * strlen($to) * strlen($title) * 
					strlen($summary) == 0) {
					
					echo 'All fields must not be empty!';
				} else {
					if (($price < 0) || ($occ <= 0)) {
						echo 'All numbers must be positive!';
					} else {
						if (!$from || !$to || $to < $from) {
							echo 'Invalid dates!';
						} else {
							if (strlen($title) > 100 || strlen($summary) > 1000) {
								echo 'Title or summary is too long!';
							} else {
								if (isset($_POST['amenities'])) {
									$amenities = $_POST['amenities'];
									foreach ($amenities as $amenity) {
										$amenity = htmlentities($amenity);
									}
								} else
									$amenities = array();
								$listing = array(
									"email" => $_SESSION['email'],
									"location" => $location,
									"city" => $city,
									"state" => $state,
									"N" => $N,
									"W" => $W,
									"from" => $from,
									"to" => $to,
									"price" => $price,
									"title" => $title,
									"summary" => $summary,
									"occ" => $occ,
									"room" => $room,
									"building" => $building,
									"imgs" => $imgs,
									"amenities" => $amenities,
									"publish" => true,
									"comments" => array()
								);
								
								if (isset($_POST['save'])) { // If editing
									if ($l['email'] == $_SESSION['email']) {
										$listing['_id'] = new MongoId($id);
										$listing['publish'] = $l['publish'];
										$listing['lastedittime'] = time();
										$L->save($listing);
									}
								} else { // If creating
									$listing['time'] = time();
									$listing['views'] = 0;
									$listid = $L->add($listing);
								
									$_SESSION['listing'] = $listid;
								}
							}
						}
					}
				}
			}
		}
	}
	
	require_once('footer.php');
?>