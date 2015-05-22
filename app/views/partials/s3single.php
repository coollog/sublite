<input type="hidden" required name="<?php vecho('s3name'); ?>" value="<?php vecho('s3link'); ?>" />

<subheadline><?php vecho('s3title'); ?></subheadline>

<div class="iframe"><iframe class="S3" src="<?php echo $GLOBALS['dirpre'] ?>../S3.php?name=<?php vecho('s3name'); ?>"></iframe></div>

<subheadline>Current Photo</subheadline>
<div class="img" name="<?php vecho('s3name'); ?>"><img src="<?php vecho('s3link'); ?>" /></div>

<script>
  function addImg<?php vecho('s3name'); ?>(url, name) {
    $('.img[name=' + name + ']').html('<img class="img" src="' + url + '" />');
    $('input[name=' + name + ']').val(url);
  }
</script>