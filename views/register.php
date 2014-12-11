<panel class="form">
  <div class="content">
    <headline>Register as a Recruiter!</headline>
    <form method="post">
      <div class="form-slider"><label for="firstname">First Name</label><input type="text" id="firstname" name="firstname" value="<?php vecho('firstname'); ?>" required /></div>
      <div class="form-slider"><label for="lastname">Last Name</label><input type="text" id="lastname" name="lastname" value="<?php vecho('lastname'); ?>" required /></div>
      <div class="form-slider"><label for="title">Job Title</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" required /></div>
      <div class="form-slider"><label for="company">Company</label><input type="text" id="company" name="company" value="<?php vecho('company'); ?>" required /></div>
      <div class="form-slider"><label for="email">Email</label><input type="email" id="email" name="email" value="<?php vecho('email'); ?>" required /></div>
      <div class="form-slider"><label for="pass">Password (6 chars min)</label><input type="password" id="pass" name="pass" required pattern=".{6,}" /></div>
      <div class="form-slider"><label for="pass2">Confirm Password</label><input type="password" id="pass2" name="pass2" required pattern=".{6,}" /></div>
      <?php vnotice(); ?>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>