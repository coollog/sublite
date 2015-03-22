<?php
  global $MSublet, $MStudent;

  $sublets = $MSublet->getAll();

  foreach ($sublets as $sublet) {
    $sublet['summary'] = str_replace('<br />', '', $sublet['summary']);

    $MSublet->save($sublet, false);
  }
?>