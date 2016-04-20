<?php
  global $MMessage;

  $messages = $MMessage->getAll();

  foreach ($messages as $message) {
    if(!isset($message["time"])) {
      $recentTime = 0;
      foreach($message["replies"] as $reply) {
        if($reply["time"] > $recentTime)
          $recentTime = $reply["time"];
      }
      $message["time"] = $recentTime;
    }
    $MMessage->save($message);
  }
?>