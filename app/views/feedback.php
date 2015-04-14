<panel class="form">
  <div class="content">
    <headline>Report a Bug/Feedback Form</headline>
    <form method="post">
      <?php vnotice(); ?>
      <div class="form-slider"><label for="name">Name</label><input type="text" id="name" name="name" value="<?php vecho('Lname'); ?>" required /></div>
      <div class="form-slider"><label for="email">Email</label><input type="email" id="email" name="email" value="<?php vecho('Lemail'); ?>" required /></div>
      <left>Please describe the bug (what you did, what the bug was, what were you trying to do) or write your feedback:</left>
      <div class="form-slider"><textarea id="feedback" name="feedback" required><?php vecho('feedback'); ?></textarea></div>
      <br>
      <left><input type="checkbox" name="terms2" id="terms2" value="agree" required /> <label for="terms2">I have read, understand, and agree to be bound by the <a href="terms.php" style="color:#035d75">Terms of Service.</a></label></left>
      <?php vnotice(); ?>
      <input type="submit" name="send" value="Send Report" />
    </form>
  </div>
</panel>