<?php
  require_once($GLOBALS['dirpre'].'includes/functions/Encoding.php');
  use \ForceUTF8\Encoding;
  // Utility functions
  function clean($s) {
    if (is_array($s)) {
      foreach ($s as $key => $val) {
        $s[$key] = clean($s[$key]);
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
  function fdatelong($timestamp) { return date('F j, Y', $timestamp); }

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
      'eric.yu@yale.edu',
      'edward.she@yale.edu',
      'dean.li@yale.edu',
      'nicolas.jimenez@yale.edu'
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

    foreach ($arr as $i => $entry) {
      $hashKey = (string) $entry[$key];
      switch ($valType) {
        case 'value': $hash[$hashKey] = $entry; break;
        case 'index': $hash[$hashKey] = $i; break;
      }
    }
    return $hash;
  }

  function arrayToSet(array $arr) {
    $set = [];
    foreach ($arr as $val) {
      $val = (string)$val;
      $set[$val] = true;
    }
    return $set;
  }

  /**
   * Takes an array of data, and splits it into multiple arrays grouped by a
   * certain field in the data.
   */
  function mapDataArrayByField(array $dataArray, $switchFunc, array $keyMap) {
    $map = [];

    foreach ($keyMap as $key) {
      $map[$key] = [];
    }

    foreach ($dataArray as $data) {
      $keyMapKey = $switchFunc($data);

      if (!isset($keyMap[$keyMapKey])) continue;

      $key = $keyMap[$keyMapKey];
      $map[$key][] = $data;
    }

    return $map;
  }

  function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
  }

  /**
   * @param $value
   * @return mixed
   */
  function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
      $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
      $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
      $result = str_replace($escapers, $replacements, $value);
      return $result;
  }

  function toJSON(array $arr) {
    function escapeJson(array $arr) {
      $newArr = [];
      foreach ($arr as $key => $value) {
        $newKey = escapeJsonString($key);
        $val = $arr[$key];
        if (is_array($val)) {
          $val = escapeJson($val);
        } else {
          $val = escapeJsonString($val);
        }
        $newArr[$newKey] = $val;
      }
      return $newArr;
    }

    $arr = escapeJson($arr);
    return strip_tags(json_encode($arr, JSON_HEX_QUOT));
  }

  function MongoIdArray(array $arr) {
    foreach ($arr as &$_id) {
      $_id = new MongoId($_id);
    }
    return $arr;
  }

  function isFacebookBot() {
    if (!isset($_SERVER["HTTP_USER_AGENT"])) return false;
    return
      strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") !== false ||
      strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") !== false;
  }

  function currentPageUrl() {
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ?
      'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
    $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
    $url .= $_SERVER["REQUEST_URI"];
    return $url;
  }
?>