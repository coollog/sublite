<?php
  function countUnread($id) {
    global $MMessage;
    $messages = array_reverse(iterator_to_array(
        $MMessage->findByParticipant($id->{'$id'})));
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

  global $MStudent;

  $students = $MStudent->getAll();

  foreach ($students as $student) {
    if (!isset($student['unread'])) {
      $studentId = $student['_id'];
      $student['unread'] = countUnread($studentId);
    }
    $MStudent->save($student);
  }

  $recruiters = RecruiterModel::getAll();

  foreach ($recruiters as $recruiter) {
    if (!isset($recruiter['unread'])) {
      $recruiterId = $recruiter['_id'];
      RecruiterModel::setUnread($recruiterId, countUnread($recruiterId));
    }
  }
?>