<?php
  require_once($GLOBALS['dirpre'].'includes/functions/Encoding.php');
  use \ForceUTF8\Encoding;
  // Utility functions
  function clean($s) {
    if (is_array($s)) {
      for ($i = 0; $i < count($s); $i ++) {
        $s[$i] = clean($s[$i]);
      }
      return $s;
    }
    // TODO Replace with preg_replace
    $s = str_replace ('“', '"', $s);
    $s = str_replace ('”', '"', $s);
    $s = str_replace ('‘', '\'', $s);
    $s = str_replace ('’', '\'', $s);
    $s = str_replace('–', '-', $s);
    $s = str_replace('—', '-', $s); //by the way these are 2 different dashes
    $s = str_replace('…', '...', $s);
    $s = trim(htmlspecialchars(Encoding::toUTF8($s), ENT_QUOTES));
    return $s;
  }
  function cleanfloat($s) {
    return str2float(clean($s));
  }
  function idcmp($id1, $id2) {
    return strval($id1) == strval($id2);
  }
  function str2int($str) {
    return (int)filter_var($str, FILTER_SANITIZE_NUMBER_INT);
  }
  function str2float($str) {
    return (float)filter_var($str, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  }
  function keywords2mregex($str) {
    $regex = "";
    $keywords = explode(" ", $str);
    foreach ($keywords as $keyword) {
      $regex .= "(?=.*$keyword)";
    }
    return new MongoRegex("/^$regex.*$/i");
  }

  function timeAgo($time, $granularity = 1) {
    $difference = time() - $time;
    $periods = array(
      'y' => 31536000,
      'w' => 604800,
      'd' => 86400,
      'h' => 3600,
      'm' => 60,
      's' => 1
    );

    if ($difference > $periods['w']) {
      return date('M j, Y', $time);
    } else {
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
  }

  function array2str($arr, $format="<b>%s</b> = '%s'", $sep="<br />\n") {
    global $f;
    $f = $format;
    return implode($sep, array_map(function ($v, $k) {
      global $f;
      return sprintf($f, $k, $v);
    }, $arr, array_keys($arr)));
  }

  function fdate($timestamp) { return date('n/j/y', $timestamp); }

  function strmax($str, $len) {
    if (strlen($str) > $len) {
      return substr($str, 0, $len-3) . '...';
    }
  }

  // Removes a value from the array
  function array_remove($array, $val) {
    if(($key = array_search($val, $array)) !== false)
      unset($array[$key]);
    return $array;
  }

  // Gets random float 0 to 1
  function rand01() {
    return mt_rand() / mt_getrandmax();
  }

  // Check if is admin
  function checkAdmin() {
    $admins = array(
      'tony.jiang@yale.edu',
      'michelle.chan@yale.edu',
      'qingyang.chen@yale.edu',
      'yuanling.yuan@yale.edu',
      'shirley.guo@yale.edu',
      'tony.chen@yale.edu',
      'alisa.melekhina@law.upenn.edu',
      'alex.croxford@yale.edu',
      'julie.slama@yale.edu',
      'joel.deleon@yale.edu'
    );
    return isset($_SESSION['email']) and in_array($_SESSION['email'], $admins);
  }

  function miles2meters($mi) {
    return $mi * 1609.344;
  }

  // Is $arr associative (vs sequential)?
  function isAssoc($arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  // Converts sequential array $arr into hash with key as $key and value as each
  // entry in $arr.
  function arrayToHashByKey(array $arr, $key, $valType = 'value') {
    $hash = array();

    for ($i = 0; $i < count($arr); $i ++) {
      $entry = $arr[$i];

      $hashKey = $entry[$key];

      switch ($valType) {
        case 'value': $hash[$hashKey] = $entry; break;
        case 'index': $hash[$hashKey] = $i; break;
      }
    }
    return $hash;
  }
?>