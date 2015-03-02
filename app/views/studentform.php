<panel class="form">
  <div class="content">
    <headline>Edit Your Profile</headline>
    <subheadline><?php vecho('email'); ?></subheadline>
    <form method="post">
      <?php vnotice(); ?>

      <div class="form-slider"><label for="name">Full Name</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" maxlength="100" required /></div>

      If you are an undergraduate student, enter your class year:
      <div class="form-slider"><label for="class">Class Year</label><input type="text" id="class" name="class" value="<?php vecho('class'); ?>" /></div>
      If you are a graduate student, enter your school:
      <div class="form-slider"><label for="school">(eg. Law School, Business School)</label><input type="text" id="name" name="school" value="<?php vecho('school'); ?>" /></div>

      <div class="form-slider"><label for="gender">Gender: </label>
        <select id="gender" name="gender" class="capitalize" required>
          <?php vecho('gender', '<option selected="selected">{var}</option>'); ?>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

      <?php 
        vpartial('s3single', array(
          's3name' => 'photo', 
          's3title' => 'Upload a profile picture *: ',
          's3link' => vget('photo')
        ));
      ?>

      <div class="form-slider"><label for="bio" class="fortextarea">About yourself: (max. 1000 characters)*</label><textarea id="bio" name="bio" maxlength="1000"><?php vecho('bio'); ?></textarea></div>

      <?php vnotice(); ?>
      <input type="submit" name="edit" value="Update Profile" />
    </form>
  </div>
</panel>