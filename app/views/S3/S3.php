<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<style>
  html, body {
    margin: 0;
    height: 100%;
    line-height: 1.25em;
    overflow: hidden;
    font-family: 'Open Sans', sans-serif;
  }
  .success {
    display: block;
    color: #00B233;
    text-transform: uppercase;
  }
  .error {
    display: block;
    color: #FF1919;
    text-transform: uppercase;
  }
</style>
<div class="container">
  <?php vnotice(); ?>
  <form method="post" enctype="multipart/form-data">
    Image (< 10MB): <input type="file" name="upload" />
    <input type="submit" value="Upload" /><br />
    <small>(.JPG/.JPEG, .PNG, .GIF only)</small>
  </form>
</div>
<script>
  function up(url) {
    console.log(url);
    <?php
      if (isset($_GET['name'])) {
        $name = $_GET['name'];
        echo "window.parent.addImg$name(url, '$name');";
      } else
        echo 'window.parent.addImg(url);';
    ?>
  }
  <?php vecho('reply'); ?>

  function resize() {
    window.parent.$('iframe.S3')
      .width($('.container').width())
      .height($('.container').height());
  }
  $('.container').resize(resize);
  resize();
  setTimeout(function() { resize(); }, 1000);
</script>