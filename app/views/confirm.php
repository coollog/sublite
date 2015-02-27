<panel class="form">
  <div class="content">
    <headline>Register as a Student!</headline>
    <subheadline><?php vecho('email'); ?></subheadline>
    <form method="post">
      <?php vnotice(); ?>
      
      <div class="form-slider"><label for="name">Full Name</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" maxlength="100" required /></div>

      <div class="form-slider"><label for="pass">Password (6 chars min)</label><input type="password" id="pass" name="pass" required pattern=".{6,}" /></div>
      <div class="form-slider"><label for="pass2">Confirm Password</label><input type="password" id="pass2" name="pass2" required pattern=".{6,}" /></div>
      
      If you are an undergraduate student, enter your school:
      <div class="form-slider"><label for="class">Class Year</label><input type="text" id="class" name="class" value="<?php vecho('class'); ?>" /></div>
      If you are a graduate student, enter your school:
      <div class="form-slider"><label for="school">(eg. Law School, Business School)</label><input type="text" id="name" name="school" value="<?php vecho('school'); ?>" /></div>

      <div class="form-slider"><label for="gender">Gender: </label>
        <select id="gender" name="gender" required>
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

      <?php vnotice(); ?>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>