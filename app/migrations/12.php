<?php
  $sublets = SubletModel::getAll();

  foreach ($sublets as $sublet) {
    $sublet['summary'] = str_replace('<br />', '', $sublet['summary']);

    SubletModel::save($sublet, false);
  }
?>