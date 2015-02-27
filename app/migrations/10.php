<?php
  global $MSublet, $MStudent;

  $sublets = $MSublet->getAll();

  foreach ($sublets as $sublet) {
    if (isset($sublet['email'])) {
      $sublet['student'] = $MStudent->get($sublet['email']);
      unset($sublet['email']);
    }
    if (isset($sublet['student']) and !MongoId::isValid($sublet['student'])) {
      $sublet['student'] = $sublet['student']['_id'];
    }

    $MSublet->save($sublet, false);
  }
?>