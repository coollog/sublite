<input type="hidden" required name="<?php View::echof('s3name'); ?>" value="<?php View::echof('s3link'); ?>" />

<subheadline><?php View::echof('s3title'); ?></subheadline>

<div class="iframe"><iframe class="S3" src="<?php echo $GLOBALS['dirpre'] ?>../S3.php?name=<?php View::echof('s3name'); ?>"></iframe></div>

<subheadline>Current Photo</subheadline>
<div class="img" name="<?php View::echof('s3name'); ?>"><img src="<?php View::echof('s3link'); ?>" /></div>

<script>
  function addImg<?php View::echof('s3name'); ?>(url, name) {
    $('.img[name=' + name + ']').html('<img class="img" src="' + url + '" />');
    $('input[name=' + name + ']').val(url);
  }
</script>