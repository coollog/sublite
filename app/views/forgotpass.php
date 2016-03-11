<panel class="form">
  <div class="content">
    <headline>Forgot Your Password?</headline>
    Enter your email address below and we'll send you an email to reset your password.
    <form method="post">
      <div class="form-slider"><label for="email">Email:</label><input type="email" id="email" name="email" value="<?php View::echof('email'); ?>" required /></div>
      <?php View::notice(); ?>
      <input type="submit" name="forgot" value="Reset Password" />
    </form>
  </div>
</panel>
