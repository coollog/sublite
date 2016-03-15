<style>
  .messages {
    text-align: left;
    width: 100%;
    min-height: 900px;
  }
  table {
    border-collapse: collapse;
  }

  .mleft {
    background: #f4f3ee;
    width: 40%;
    vertical-align: top;
  }
  .mright {
    vertical-align: top;
  }
  .bottom {
    vertical-align: bottom;
    padding: 20px 40px;
  }
  .bottom headline {
    margin-bottom: -20px;
    line-height: 1em;
  }
  unread {
    font-size: 0.5em;
    color: #ffd800;
  }

  none {
    padding: 20px 40px;
    display: block;
  }

  .iblockwrapper {
    padding: 20px 40px;
    cursor: pointer;
    color: #000;
  }
  .iblockwrapper:hover {
    background: #fff;
  }
  .iblockwrapper.current {
    background: #fff;
  }
  .iblockwrapper.unread {
    border-left: 4px solid #f33;
  }
  table.iblock {
    display: table;
    width: 100%;
  }
  table.iblock profpic {
    width: 80px;
    height: 80px;
    border-radius: 40px;
  }
  table.iblock .pp {
    width: 80px;
  }
  profpic {
    background: transparent no-repeat center center;
    background-size: cover;
    display: block;
  }
  name {
    font-size: 1.2em;
    color: #035d75;
    font-weight: 700;
  }
  time {
    opacity: 0.5;
    margin-left: 1em;
  }
  data {
    display: block;
    margin-left: 0.5em;
  }
  text {
    display: block;
  }

  .themessage {
    word-wrap: break-word;
    max-width: 150px;
  }
  .mblockwrapper {
    padding: 20px 40px;
    border-bottom: 1px solid #eee;
  }
  table.mblock {
    display: table;
    width: 100%;
  }
  table.mblock profpic {
    width: 100px;
    height: 100px;
    border-radius: 50px;
    margin: 0 20px;
  }
  table.mblock textarea {
    width: 100%;
    margin: 0;
  }
  table.mblock .pp {
    width: 140px;
  }
  table form {
    max-width: none;
  }
</style>

<panel style="padding: 0;">
  <table class="messages"><tr>

    <td class="mleft bottom" style="height: 2em;">
      <headline>Inbox <unread><?php View::echof('unread'); ?> unread</unread></headline>
    </td>
    <td class="mright bottom" style="height: 2em;">
      <headline><?php View::echof('to'); ?></headline>
    </td>

  </tr><tr>

    <td class="mleft">
      <?php if (count(View::get('messages')) == 0) { ?>
        <none>No messages so far.</none>
      <?php } ?>
      <?php foreach (View::get('messages') as $m) { ?>
        <a href="?id=<?php echo $m['_id']; ?>">
          <div class="iblockwrapper <?php if ($m['current']) echo 'current'; ?> <?php if (!$m['read']) echo 'unread'; ?>">
            <table class="iblock"><tr>
              <td class="pp"><profpic style="background-image: url('<?php echo $m['frompic'] ?>');"></profpic></td>
              <td class="themessage"><data>
                <name><?php echo $m['fromname']; ?></name><time><?php echo $m['time']; ?></time>
                <text><?php echo autolink(($m['fromname'] == View::get('Lname') ? 'You' : $m['fromname']) . ': ' . $m['msg']); ?></text>
              </data></td>
            </tr></table>
          </div>
        </a>
      <?php } ?>
    </td>
    <td class="mright">
      <?php if (!is_null(View::get('current'))) { ?>
        <?php foreach (View::get('current') as $m) {
          $isStudent = StudentModel::exists(new MongoId($m['from']));
          $profilelink = $isStudent ?
              $GLOBALS['dirpre'] . "../student/profile?id=" . $m['from'] :
              $GLOBALS['dirpre'] . "../employers/recruiter?id=" . $m['from'];
        ?>
          <div class="mblockwrapper">
            <table class="mblock"><tr>
              <td class="pp"><a href="<?php echo $profilelink; ?>"><profpic style="background-image: url('<?php echo $m['frompic'] ?>');"></profpic></a></td>
              <td class="themessage"><data>
                <a href="<?php echo $profilelink; ?>"><name><?php echo $m['fromname']; ?></name></a><time><?php echo $m['time']; ?></time>
                <text><?php echo nl2br(autolink($m['msg'])); ?></text>
              </data></td>
            </tr></table>
          </div>
        <?php } ?>
        <div class="mblockwrapper">
          <table class="mblock">
            <td class="pp"><profpic style="background-image: url('asdf');"></profpic></td>
            <td><data>
              <form method="post" action="?id=<?php View::echof('currentid'); ?>">
                <textarea id="msg" name="msg" required maxlength="2000" placeholder="Write Your Message:"><?php View::echof('msg'); ?></textarea>
                <?php View::notice(); ?>
                <right><input type="submit" name="reply" value="Send" /></right>
              </form>
            </data></td>
          </table>
        </div>
      <?php } ?>
    </td>
  </tr></table>
</panel>