<?php
	require_once('error.php');
	
	// Important settings
	//$domain = 'localhost:8080/v6';
	//$GLOBALS['mongouri'] = "mongodb://coollog:#Sublite2014@localhost:27017/sublite";
	$pass = "5ed527b";
	require_once('pass.php');
	//$domain = '54.186.72.122';
	$domain = 'sublite.net/housing';
	$path2Root = '..';
	date_default_timezone_set('America/New_York');
	
	header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	require_once('db.php');
	require_once('schools.php');
	require_once('geocode.php');
	
	session_start();
	$defaultAfterLogin = 'search.php';
	if (!isset($_SESSION['afterLogin'])) {
		$_SESSION['afterLogin'] = $defaultAfterLogin;
	}
	if (isset($_SESSION['email'])) {
		if (!$E->validate($_SESSION['email'])) {
			require('aLogout.php');
			header("Location: https://$domain");
			exit();
		}
	} else {
		if (isset($requireLogin)
				and strpos($_SERVER['REMOTE_ADDR'], '173.252.73.') === false) { // Facebook
			$_SESSION['afterLogin'] = $path2Root . $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'];
			header("Location: https://$domain");
			exit();
		}
	}
	
	function setJSVar($name, $val) {
		echo "<script>var $name = $val;</script>";
	}
	function cssCross($css) {
		echo "$css-webkit-$css-moz-$css-ms-$css-o-$css";
	}
	function distance($lat1, $lon1, $lat2, $lon2) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		return $miles;
	}
	function error($msg) {
		require_once('htmlheader.php');
		echo $msg;
	}
	function getURL() {
		$pageURL = 'https://';
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	function JSfriend($str) {
		$noquote = str_replace('"', '\"', $str);
		$nonl = preg_replace('/(\r\n|\n|\r)/', '<br/>', $noquote);
		return $nonl;
	}
	function JSfriend2($str) {
		$noquote = str_replace("'", "\\'", $str);
		return $noquote;
	}
	function JSfriendTextarea($str) {
		$noquote = str_replace('"', '\"', $str);
		$nonl = preg_replace('/(\r\n|\n|\r)/', '\r\n', $noquote);
		return $nonl;
	}
	function checkAdmin() {
		$admins = array('qingyang.chen@yale.edu', 'alisa.melekhina@law.upenn.edu', 'yuanling.yuan@yale.edu', 'shirley.guo@yale.edu');
		if (!in_array($_SESSION['email'], $admins)) {
			die('Access denied!');
		}
	}
	function br2nl($str) {
		return preg_replace('#<br\s*/?>#i', "\n", $str);
	}
	function timeAgo($time, $granularity = 1) {
		$difference = time() - $time;
		$periods = array(
			'y' => 31536000,
			'm' => 2628000,
			'w' => 604800, 
			'd' => 86400,
			'h' => 3600,
			'm' => 60,
			's' => 1
		);

		$retval = '';
		foreach ($periods as $key => $value) {
			if ($difference >= $value) {
				$time = floor($difference/$value);
				$difference %= $value;
				$retval .= ($retval ? ' ' : '').$time;
				$retval .= $key;
				$granularity --;
			}
			if ($granularity <= 0) { break; }
		}
		if ($retval == '') {
			return 'Just now.';
		} else
			return $retval . ' ago';
	}
	function https($uri) {
		return str_replace("http://", "https://", $uri);
	}
	function clean($str) {
		return trim(utf8_encode(nl2br(htmlentities($str))));
	}

?>