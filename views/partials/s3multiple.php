<div class="inputs" name="<?php vecho('s3name'); ?>"></div>

<subheadline><?php vecho('s3title'); ?></subheadline>

<div class="iframe"><iframe class="S3" src="S3.php?name='<?php vecho('s3name'); ?>'"></iframe></div>

<subheadline>Current Photos</subheadline>
<div class="img" name="<?php vecho('s3name'); ?>">None</div>

<script>
  function addImg(url, name) {
    if ($('.img[name='+name+']').html() == 'None') $('.img[name='+name+']').html('');

    $('.inputs[name='+name+']')
      .prepend('<input class="imginput" type="hidden" name="'+name+'[]" value="' + url + '" />');
    $('.img[name='+name+']')
      .prepend('<input type="button" class="remove" url="' + url + '" value="Remove" />')
      .prepend('<img class="img" src="' + url + '" />');

    $('.img[name='+name+'] .remove[url="'+url+'"]').click(function() {console.log('clicked ' + url);
      $('.inputs[name='+name+'] .imginput[value="'+url+'"]').remove();
      $('.img[name='+name+'] img.img[src="'+url+'"]').remove();
      $('.img[name='+name+'] .remove[url="'+url+'"]').remove();

      if ($('.img[name='+name+']').html() == '') $('.img').html('None');
    });
  }
  <?php
    $name = vget('s3name');
    foreach (vget('s3links') as $link) {
      echo "addImg('$link', '$name');";
    }
  ?>
</script>