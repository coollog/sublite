<?php
	$requireLogin = true;
	require_once('header.php');	
	checkAdmin();
	if (isset($_POST['loginas'])) {
		$_SESSION['email'] = $_POST['loginas'];
		header("Location: http://$domain");
		exit();
	}
	require_once('htmlheader.php');
	
	$accounts = $E->cEmails->find(array(), array('_id' => true, 'email' => true, 'unsubscribe' => true));
	$accountscount = $accounts->count();
	$accountsdump = array();
	foreach ($accounts as $doc) {
		array_push($accountsdump, $doc);
	}
	$registered = $E->cEmails->find(array('pass' => array('$exists' => true)), array('_id' => true, 'email' => true));
	$registeredcount = $registered->count();
	$registereddump = array();
	foreach ($registered as $doc) {
		array_push($registereddump, $doc);
	}
	
	$nonregisteredcount = $accountscount - $registeredcount;
	$nonregistereddump = array();
	foreach ($accounts as $doc) {
		if (!in_array($doc, $registereddump)) {
			array_push($nonregistereddump, $doc);
		}
	}
	
	$schools = array();
	$schoolscount = count($S->LUT);
	$accounts2 = $accounts;
	foreach ($S->LUT as $domain=>$name) {
		$count = 0;
		foreach ($registered as $doc) {
			if (strpos($doc['email'], "@$domain") !== false) {
				$count ++;
			}
		}
		array_push($schools, array('name' => $name, 'count' => $count));
	}
	$name = array();
	$count = array();
	foreach ($schools as $row) {
		$name[] = $row['name'];
		$count[] = $row['count'];
	}
	array_multisort($count, SORT_DESC, $name, $schools);
	foreach ($schools as $row) {
		$schoolsdump[] = $row['name'] . ' - ' . $row['count'];
	}
	
	$listings = $L->cListings->find();
	$listingscount = $listings->count();
	$listingsdump = array();
	$cities = array();
	$comments = array();
	foreach ($listings as $doc) {
		array_push($listingsdump, $doc);
		
		if (isset($_REQUEST['citycount'])) {
			$city = getCity($doc['city'] . ', ' . $doc['state']);
			if ($city != null) {
				if (!in_array($city, $cities))
					array_push($cities, $city);
			}
		}
		foreach ($doc['comments'] as $comment) {
			$comment['l'] = $doc['_id'];
			array_push($comments, $comment);
		}
	}
	$citycount = count($cities);
	if (!isset($_REQUEST['citycount'])) {
		$cities = array('Add ?citycount to the URL to see cities.');
	}
	$commentscount = count($comments);
	
	$unpublished = $L->cListings->find(array('publish' => false));
	$unpublishedcount = $unpublished->count();
	$unpublisheddump = array();
	foreach ($unpublished as $doc) {
		array_push($unpublisheddump, $doc);
	}
	
	$subscribeddump = array();
	foreach ($accounts as $doc) {
		if (!isset($doc['unsubscribe']) or !$doc['unsubscribe'])
			array_push($subscribeddump, $doc['email']);
	}
	$subscribedcount = count($subscribeddump);
	
	$unknown = array();
	foreach ($accounts as $doc) {
		if (!$S->hasSchoolOf($doc['email']))
			array_push($unknown, $doc['email']);
	}
	$unknowncount = count($unknown);
	
	
	$listings = $L->cListings->find(array(), array('email' => true));
	$listers = array();
	foreach ($listings as $doc) {
		if (!in_array($doc['email'], $listers)) {
			array_push($listers, $doc['email']);
		}
	}
	$listerscount = count($listers);
	
	// Code to update accounts:
	$docs = $E->cEmails->find();
	foreach ($docs as $doc) {
		if (!isset($doc['confirm'])) {
			$id = md5(uniqid($doc['email'], true));
			$doc['confirm'] = $id;
			$E->cEmails->save($doc);
		}
		if (!isset($doc['school'])) {
			$doc['school'] = '';
			$doc['class'] = '';
			$E->cEmails->save($doc);
		}
		if (!isset($doc['name'])) {
			$doc['name'] = '';
			$E->cEmails->save($doc);
		}
	}
	// Code to update listings
	$docs = $L->cListings->find();
	foreach ($docs as $doc) {
		if (!isset($doc['city'])) {
			$doc['city'] = '';
			$doc['state'] = '';
		}
		if (!isset($doc['amenities'])) {
			$doc['amenities'] = array();
		}
		if (!isset($doc['room'])) {
			$doc['room'] = '';
			$doc['building'] = '';
		}
		if ($doc['room'] == '') {
			$doc['room'] = 'Other';
		}
		if ($doc['building'] == '') {
			$doc['building'] = 'Other';
		}
		if (!isset($doc['views'])) {
			$doc['views'] = 0;
		}
		if (!isset($doc['comments'])) {
			$doc['comments'] = array();
		}
		$L->cListings->save($doc);
	}
?>

<style>
	.cpblock {
		display: inline-block;
		margin: 1em;
		padding: 1em;
		border: 1px solid #ccc;
		vertical-align: top;
	}
	.list {
		width: 30em;
		height: 10em;
		overflow: auto;
		border: 1px solid #ccc;
		padding: 1em;
	}
	h3 {
		font-size: 1.3em;
		font-weight: normal;
		margin: 2em 0 0.5em 0;
		line-height: 1.1em;
		letter-spacing: 0.5px;
	}
</style>

<div class="cpblock">
	<h3>Total Accounts: <?php echo $accountscount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($accountsdump as $doc) {
				$id = $doc['_id'];
				$email = $doc['email'];
				echo "<a href='profile.php?p=$id'>$email</a><br />";
			}
		?>
	</div>
	<a href="getInfo.php"><input type="button" class="button orange" value="Get Info" /></a>
</div>
<div class="cpblock">
	<h3>Registered Accounts: <?php echo $registeredcount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($registereddump as $doc) {
				$id = $doc['_id'];
				$email = $doc['email'];
				echo "<a href='profile.php?p=$id'>$email</a><br />";
			}
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Non-confirmed Accounts: <?php echo $nonregisteredcount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($nonregistereddump as $doc) {
				$id = $doc['_id'];
				$email = $doc['email'];
				echo "<a href='profile.php?p=$id'>$email</a><br />";
			}
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Universities We Have - <?php echo $schoolscount; ?>!</h3>
	<br />
	<div class="list">
		<?php
			echo implode('<br />', $schoolsdump);
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Subscribed Accounts: <?php echo $subscribedcount; ?></h3>
	<br />
	<div class="list">
		<?php
			echo implode(", ", $subscribeddump);
		?>
	</div>
	<a href="sendnewsletter.php"><input type="button" class="button orange" value="Send Newsletter" /></a>
</div>
<div class="cpblock">
	<h3>Listings: <?php echo $listingscount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($listingsdump as $doc) {
				$id = $doc['_id'];
				$title = $doc['title'];
				echo "<a href='listing.php?l=$id'>$title</a><br />";
			}
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Cities: <?php echo $citycount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($cities as $city) {
				echo "$city<br />";
			}
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Comments: <?php echo $commentscount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($comments as $comment) {
				$id = $comment['l'];
				$text = $comment['email'] . ' &mdash; ' . $comment['comment'];
				echo "<a href='listing.php?l=$id'>$id</a>: $text<br />";
			}
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Unpublished: <?php echo $unpublishedcount; ?></h3>
	<br />
	<div class="list">
		<?php
			foreach ($unpublisheddump as $doc) {
				$id = $doc['_id'];
				$title = $doc['title'];
				echo "<a href='listing.php?l=$id'>$title</a><br />";
			}
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Login As</h3>
	<br />
	<form method="post">
		<input type="text" name="loginas" placeholder="email" />
		<input type="submit" class="button orange" value="Login As" />
	</form>
</div>
<div class="cpblock">
	<h3>Unknown Email Domains: <?php echo $unknowncount; ?></h3>
	<br />
	<div class="list">
		<?php
			echo implode("<br />", $unknown);
		?>
	</div>
</div>
<div class="cpblock">
	<h3>Listers: <?php echo $listerscount; ?></h3>
	<br />
	<div class="list">
		<?php
			echo implode("<br />", $listers);
		?>
	</div>
</div>

<?php
	
	require_once('htmlfooter.php');
    require_once('footer.php'); 
?>