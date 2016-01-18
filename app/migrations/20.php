<?php
  function countUnread($id) {
    $messages = array_reverse(iterator_to_array(
        MessageModel::findByParticipant($id->{'$id'})));
    $unread = 0;
    foreach ($messages as $m) {
      $reply = array_pop($m['replies']);
      $reply['_id'] = $m['_id'];

      $from = $reply['from'];
      if (!$reply['read']) {
        $reply['read'] = (strcmp($from, $id) == 0);
      }
      if (!$reply['read']) $unread++;
    }
    return $unread;
  }

  $students = StudentModel::getAll();

  foreach ($students as $student) {
    if (!isset($student['unread'])) {
      $studentId = $student['_id'];
      $student['unread'] = countUnread($studentId);
      StudentModel::save($student);
    }
  }

  $recruiters = RecruiterModel::getAll();

  foreach ($recruiters as $recruiter) {
    if (!isset($recruiter['unread'])) {
      $recruiterId = $recruiter['_id'];
      RecruiterModel::setUnread($recruiterId, countUnread($recruiterId));
    }
  }
?>