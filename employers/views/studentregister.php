<panel class="form">
  <div class="content">
    <headline>Student Registration</headline>
    <form method="post">
      <subheadline>What's your .edu email address?</subheadline>
      <input type="email" id="email" name="email" value="<?php vecho('email'); ?>" required />
      <?php vnotice(); ?>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>