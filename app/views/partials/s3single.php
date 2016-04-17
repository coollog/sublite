<input type="hidden" required
       name="<?php View::echof('s3name'); ?>"
       value="<?php View::echof('s3link'); ?>" />

<?php if ($title = View::get('s3title')) { ?>
  <subheadline><?php echo $title; ?></subheadline>
<?php } ?>

<?php View::partial('S3/image', [ 's3name' => View::get('s3name') ]); ?>

<subheadline>Current Photo</subheadline>
<div class="img" name="<?php View::echof('s3name'); ?>">
  <img src="<?php View::echof('s3link'); ?>" />
</div>

<script>
  if (typeof addImg === 'undefined') addImg = {};

  addImg['<?php View::echof('s3name'); ?>'] = function (url) {
    $('.img[name=<?php View::echof('s3name'); ?>]')
      .html('<img class="img" src="' + url + '" />');
    $('input[name=<?php View::echof('s3name'); ?>]').val(url);
  };
</script>