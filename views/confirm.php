<panel class="form">
  <div class="content">
    <headline>Register as a Student!</headline>
    <subheadline><?php vecho('email'); ?></subheadline>
    <form method="post">
      <div class="form-slider"><label for="name">Full Name</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" maxlength="100" required /></div>
      <div class="form-slider"><label for="pass">Password (6 chars min)</label><input type="password" id="pass" name="pass" required pattern=".{6,}" /></div>
      <div class="form-slider"><label for="pass2">Confirm Password</label><input type="password" id="pass2" name="pass2" required pattern=".{6,}" /></div>
      <div class="form-slider"><label for="class">Class Year</label><input type="text" id="class" name="class" value="<?php vecho('class'); ?>" required /></div>
      If you are a graduate student, enter your school:
      <div class="form-slider"><label for="school">(eg. Law School, Business School)</label><input type="text" id="name" name="school" value="<?php vecho('school'); ?>" /></div>
      <?php vnotice(); ?>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>