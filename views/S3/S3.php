<?php require_once('views/view.php'); ?>

<style>
  html, body {
    margin: 0;
    height: 100%;
    overflow: hidden;
  }
</style>
<script>
  $(function() {
    function resize() {
      window.parent.$('iframe.S3Upload')
        .width($('.container').width())
        .height($('.container').height());
    }
    $('.container').resize(resize);
    resize();
  });
  function up(url) {
    console.log(url);
    window.parent.addImg(url);
  }
  <?php vecho('reply'); ?>
</script>
<div class="container">
  <div class="msg"><?php vecho('err'); ?></div>
  <form method="post" enctype="multipart/form-data">
    Image (< 10MB): <input type="file" name="upload" />
    <input type="submit" value="Upload" />
  </form>
</div>