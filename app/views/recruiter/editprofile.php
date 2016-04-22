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
      <?php
        echo ' &nbsp; ' .
        View::linkTo('<input type="button" value="View Profile" /><br /><br />',
                     'recruiter',
                     [ 'id' => View::get('L_id')->{'$id'} ]);
      ?>

      <?php View::notice(); ?>

      <div class="form-slider">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname"
               value="<?php View::echof('firstname'); ?>" required />
      </div>

      <div class="form-slider">
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname"
               value="<?php View::echof('lastname'); ?>" required />
      </div>

      <div class="form-slider">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title"
               value="<?php View::echof('title'); ?>" required />
      </div>

      <div class="form-slider">
        <label for="phone">Phone number:</label>
        <input type="text" id="phone" name="phone"
               value="<?php View::echof('phone'); ?>" required />
      </div>

      <?php
        View::partial('s3single', [
          's3name' => 'photo',
          's3title' => '',
          's3link' => View::get('photo')
        ]);
      ?>

      <?php View::notice(); ?>
      <right><input type="submit" name="edit" value="Save Profile" /></right>
    </form>
  </div>
</panel>

<script>
  formunloadmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>