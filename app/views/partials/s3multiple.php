<div class="inputs" name="<?php View::echof('s3name'); ?>"></div>

<?php if ($title = View::get('s3title')) { ?>
  <subheadline><?php echo $title; ?></subheadline>
<?php } ?>

<?php View::partial('S3/image', [ 's3name' => View::get('s3name') ]); ?>

<subheadline>Current Photos</subheadline>
<div class="img" type="multiple"
     name="<?php View::echof('s3name'); ?>">None</div>

<script>
  if (typeof addImg === 'undefined') addImg = {};

  addImg['<?php View::echof('s3name'); ?>'] = function (url) {
    var name = '<?php View::echof('s3name'); ?>';

    // Clear the current photos box if it says 'None'.
    var currentPhotos = $('.img[name='+name+']');
    if (currentPhotos.html() == 'None') currentPhotos.html('');

    $('.inputs[name='+name+']').prepend(
      '<input class="imginput" type="hidden"' +
      '       name="'+name+'[]" value="' + url + '" />');
    $('.img[name='+name+']').prepend('<img class="img" src="'+url+'" />');

    $('.img[name='+name+'] img[src="'+url+'"]').click(function() {
      $('.inputs[name='+name+'] .imginput[value="'+url+'"]').remove();
      $(this).remove();

      var currentPhotos = $('.img[name='+name+']');
      if (currentPhotos.html() == '') currentPhotos.html('None');
    });
  };

  <?php
    if (!is_null(View::get('s3links'))) {
      $name = View::get('s3name');
      foreach (View::get('s3links') as $link) {
        echo "addImg['$name']('$link', '$name');";
      }
    }
  ?>
</script>