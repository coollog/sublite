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
      <?php echo ' &nbsp; ' . View::linkTo('<input type="button" value="View Profile" /><br /><br />', 'recruiter', array('id' => View::get('L_id')->{'$id'})); ?>

      <?php View::notice(); ?>

      <div class="form-slider"><label for="firstname">First Name:</label><input type="text" id="firstname" name="firstname" value="<?php View::echof('firstname'); ?>" required /></div>

      <div class="form-slider"><label for="lastname">Last Name:</label><input type="text" id="lastname" name="lastname" value="<?php View::echof('lastname'); ?>" required /></div>

      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php View::echof('title'); ?>" required /></div>

      <div class="form-slider"><label for="phone">Phone number:</label><input type="text" id="phone" name="phone" value="<?php View::echof('phone'); ?>" /></div>

      <input type="hidden" name="photo" value="<?php View::echof('photo'); ?>" />
      <subheadline>Upload Photo</subheadline>
      <div class="iframe"><iframe class="S3" src="S3.php"></iframe></div>
      <subheadline>Current Photo</subheadline>
      <div class="img"><img src="<?php View::echof('photo'); ?>" /></div>

      <?php View::notice(); ?>
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