<?php
  global $MSublet, $MStudent;

  $sublets = $MSublet->getAll();

  foreach ($sublets as $sublet) {
    $sublet['price'] = str2float($sublet['price']);
    if (preg_match('/^\$/', $sublet['price'])) {
      $sublet['price'] = preg_replace('/^\$/', '', $sublet['price']).'<br/>';
    }
    $MSublet->save($sublet, false);
  }
?>