<style>
  subheadline {
    line-height: 2em;
    margin-top: 0.5em;
  }
</style>

<panel class="form">
  <div class="content">
    <headline>Edit Profile</headline>
    <form method="post">
      <?php echo ' &nbsp; ' . vlinkto('<input type="button" value="View Profile" /><br /><br />', 'recruiter', array('id' => vget('L_id')->{'$id'})); ?>

      <?php vnotice(); ?>

      <div class="form-slider"><label for="firstname">First Name:</label><input type="text" id="firstname" name="firstname" value="<?php vecho('firstname'); ?>" required /></div>

      <div class="form-slider"><label for="lastname">Last Name:</label><input type="text" id="lastname" name="lastname" value="<?php vecho('lastname'); ?>" required /></div>

      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" required /></div>

      <div class="form-slider"><label for="phone">Phone number:</label><input type="text" id="phone" name="phone" value="<?php vecho('phone'); ?>" /></div>

      <input type="hidden" name="photo" value="<?php vecho('photo'); ?>" />
      <subheadline>Upload Photo</subheadline>
      <div class="iframe"><iframe class="S3" src="S3.php"></iframe></div>
      <subheadline>Current Photo</subheadline>
      <div class="img"><img src="<?php vecho('photo'); ?>" /></div>

      <?php vnotice(); ?>
      <right><input type="submit" name="edit" value="Save Profile" /></right>
    </form>
  </div>
</panel>

<script>
  function addImg(url) {
    $('.img').html('<img class="img" src="' + url + '" />');
    $('input[name=photo]').val(url);
  }
  $(function() {

  });
  formunloadmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>