<style>
  .thread {
    border: 1px solid #666;
  }
  .reply.unread {
    background: #ccc;
  }
</style>

<?php
  $mlist = vget('mlist');

  foreach ($mlist as $m) {
?>
    <div class="thread">
      <?php
        foreach ($m as $r) {
          $name = $r['name'];
          $email = $r['email'];
          $time = $r['time'];
          $msg = $r['msg'];
          $read = $r['read'] ? '' : 'unread';
      ?>
          <div class="reply <?php echo $read; ?>">
            <?php echo $name; ?> - <?php echo $email; ?> | <?php echo $time; ?>
            <br />
            <?php echo $msg; ?>
          </div>
      <?php
        }
      ?>
    </div>
<?php
  }
?>