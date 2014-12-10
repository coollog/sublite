<panel class="form">
  <div class="content">
    <headline>Register as a Recruiter!</headline>
    <form method="post">
      <div class="form-slider"><label for="firstname">First Name</label><input type="text" id="firstname" name="firstname" value="<?php vecho('firstname'); ?>" /></div>
      <div class="form-slider"><label for="lastname">Last Name</label><input type="text" id="lastname" name="lastname" value="<?php vecho('lastname'); ?>" /></div>
      <div class="form-slider"><label for="title">Job Title</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" /></div>
      <div class="form-slider"><label for="company">Company</label><input type="text" id="company" name="company" value="<?php vecho('company'); ?>" /></div>
      <div class="form-slider"><label for="email">Email</label><input type="text" id="email" name="email" value="<?php vecho('email'); ?>" /></div>
      <div class="form-slider"><label for="pass">Password</label><input type="password" id="pass" name="pass" /></div>
      <div class="form-slider"><label for="pass2">Confirm Password</label><input type="password" id="pass2" name="pass2" /></div>
      <?php vnotice(); ?>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>