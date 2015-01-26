<div class="inputs" name="<?php vecho('s3name'); ?>"></div>

<subheadline><?php vecho('s3title'); ?></subheadline>

<div class="iframe"><iframe class="S3" src="S3.php?name=<?php vecho('s3name'); ?>"></iframe></div>

<subheadline>Current Photos</subheadline>
<div class="img" type="multiple" name="<?php vecho('s3name'); ?>">None</div>

<script>
  function addImg<?php vecho('s3name'); ?>(url, name) {
    if ($('.img[name='+name+']').html() == 'None') $('.img[name='+name+']').html('');

    $('.inputs[name='+name+']')
      .prepend('<input class="imginput" type="hidden" name="'+name+'[]" value="' + url + '" />');
    $('.img[name='+name+']')
      .prepend('<img class="img" src="' + url + '" />');

    $('.img[name='+name+'] img[src="'+url+'"]').click(function() {
      $('.inputs[name='+name+'] .imginput[value="'+url+'"]').remove();
      $(this).remove();

      if ($('.img[name='+name+']').html() == '') $('.img[name='+name+']').html('None');
    });
  }
  <?php
    if (!is_null(vget('s3links'))) {
      $name = vget('s3name');
      foreach (vget('s3links') as $link) {
        echo "addImg$name('$link', '$name');";
      }
    }
  ?>
</script>