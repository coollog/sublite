<panel class="form">
  <div class="content">
    <headline>Recruiter Log In</headline>
    <form method="post">
      <div class="form-slider"><label for="email">Email</label><input type="email" id="email" name="email" value="<?php View::echof('email'); ?>" required /></div>
      <div class="form-slider"><label for="pass">Password</label><input type="password" id="pass" name="pass" required /></div>
      <?php View::notice(); ?>
      <input type="submit" name="login" value="Log In" />
      <div style="font-size: 0.8em"><a href="register">Don't Have an Account?</a></div>
      <div style="font-size: 0.8em"><a href="forgotpass">Forgot Your Password?</a></div>
      <div style="font-size: 0.8em"><a href="../login">Log In As Student</a></div>
    </form>
  </div>
</panel>