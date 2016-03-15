<panel class="form">
  <div class="content">
    <headline>Student Registration</headline>
    <form method="post">
      <subheadline>What's your .edu email address?</subheadline>
      <input type="email" id="email" name="email" value="<?php View::echof('email'); ?>" required />
      <?php View::notice(); ?>
      <input type="submit" name="register" value="Register" />
      <div style="font-size: 0.8em"><a href="login.php">Already Have an Account?</a></div>
    </form>
  </div>
</panel>