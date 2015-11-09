<style>
  .thread {
    border: 1px solid #666;
    padding: 1em;
  }
  .reply.unread {
    background: #ccc;
  }
  .thread .reply {
    display: none;
    padding: 1em;
    border: 1px solid #ccc;
  }
  .brief {
    cursor: pointer;
    background: #f8dd00;
    padding: 1em;
  }
</style>

<?php
  $mlist = vget('mlist');

  foreach ($mlist as $m) {
?>
    <div class="thread">
      <div class="brief">
        <?php
          $count = count($m['replies']);
          $participants = array();
          foreach ($m['participants'] as $p) {
            $pname = $p['name'];
            $pemail = $p['email'];
            $participants[] = "$pname ($pemail)";
          }
          $participants = implode(", ", $participants);
          $lasttime = $m['lasttime'];
        ?>
        <?php echo $count; ?> messages between <?php echo $participants; ?> | <?php echo $lasttime; ?>
      </div>
      <?php
        foreach ($m['replies'] as $r) {
          $name = $r['name'];
          $email = $r['email'];
          $time = $r['time'];
          $msg = $r['msg'];
          $read = $r['read'] ? '' : 'unread';
          $to = '';
          foreach ($r['to'] as $t) {
            $toname = $t['name'];
            $toemail = $t['email'];
            $to .= "$toname ($toemail) ";
          }
      ?>
          <div class="reply <?php echo $read; ?>">
            <div class="info">
              <?php echo $name; ?> (<?php echo $email; ?>) to <?php echo $to; ?> | <?php echo $time; ?>
            </div>
            <div class="msg">
              <?php echo $msg; ?>
            </div>
          </div>
      <?php
        }
      ?>
    </div>
<?php
  }
?>

<script>
  $(function() {
    $('.brief').click(function() {
      $(this).parent().find('.reply').slideToggle(200);
    });
  });
</script>