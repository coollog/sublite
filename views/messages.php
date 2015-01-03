<style>
  .messages {
    text-align: left;
    width: 100%;
  }
  table {
    border-collapse: collapse;
  }

  .mleft {
    background: #f4f3ee;
    width: 400px;
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

  table.iblock {
    padding: 20px 40px;
    display: table;
    width: 100%;
    cursor: pointer;
    color: #000;
  }
  table.iblock.current {
    background: #fff;
  }
  table.iblock.unread {
    border-left: 4px solid #f33;
  }
  table.iblock profpic {
    width: 80px;
    height: 80px;
    border-radius: 40px;
  }
  table.iblock .pp {
    width: 80px;
  }
  table.iblock:hover {
    background: #fff;
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
  }
  text {
    display: block;
  }

  table.mblock {
    padding: 20px 40px;
    display: table;
    width: 100%;
    border-bottom: 1px solid #eee;
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

<panel>
  <table class="messages"><tr>

    <td class="mleft bottom">
      <headline>Inbox <unread><?php vecho('unread'); ?> unread</unread></headline>
    </td>
    <td class="mright bottom">
      <headline>Message To:</headline>
    </td>

  </tr><tr>

    <td class="mleft">
      <?php if (count(vget('messages')) == 0) { ?>
        <none>No messages so far.</none>
      <?php } ?>
      <?php foreach (vget('messages') as $m) { ?>
        <a href="?id=<?php echo $m['_id']; ?>"><table class="iblock <?php if ($m['current']) echo 'current'; ?> <?php if (!$m['read']) echo 'unread'; ?>"><tr>
          <td class="pp"><profpic style="background-image: url('<?php echo $m['frompic'] ?>');"></profpic></td>
          <td><data>
            <name><?php echo $m['fromname']; ?></name><time><?php echo $m['time']; ?></time>
            <text><?php echo $m['msg']; ?></text>
          </data></td>
        </tr></table></a>
      <?php } ?>
    </td>
    <td class="mright">
      <?php if (!is_null(vget('current'))) { ?>
        <?php foreach (vget('current') as $m) { ?>
          <table class="mblock"><tr>
            <td class="pp"><profpic style="background-image: url('<?php echo $m['frompic'] ?>');"></profpic></td>
            <td><data>
              <name><?php echo $m['fromname']; ?></name><time><?php echo $m['time']; ?></time>
              <text><?php echo $m['msg']; ?></text>
            </data></td>
          </tr></table>
        <?php } ?>
        <table class="mblock">
          <td class="pp"><profpic style="background-image: url('asdf');"></profpic></td>
          <td><data>
            <form method="post">
              <textarea id="msg" name="msg" required maxlength="2000" placeholder="Your Reply Here:"><?php vecho('msg'); ?></textarea>
              <?php vnotice(); ?>
              <right><input type="submit" name="reply" value="Reply" /></right>
            </form>
          </data></td>
        </table>
      <?php } ?>
    </td>
  </tr></table>
</panel>