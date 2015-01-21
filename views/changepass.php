<panel class="form">
  <div class="content">
    <headline>Change Your Password</headline>
    <form method="post">
      <div class="form-slider"><label for="pass">New Password</label><input type="password" id="pass" name="pass" required /></div>
      <div class="form-slider"><label for="pass2">Confirm New Password</label><input type="password" id="pass2" name="pass2" required /></div>
      <?php vnotice(); ?>
      <input type="submit" name="change" value="Change" />
    </form>
  </div>
</panel>