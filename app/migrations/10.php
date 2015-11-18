<?php
  $sublets = SubletModel::getAll();

  foreach ($sublets as $sublet) {
    if (isset($sublet['email'])) {
      $sublet['student'] = StudentModel::getByEmail($sublet['email']);
      unset($sublet['email']);
    }
    if (isset($sublet['student']) and !MongoId::isValid($sublet['student'])) {
      $sublet['student'] = $sublet['student']['_id'];
    }

    SubletModel::save($sublet, false);
  }
?>