<panel>
  <div class="content">
    <?php View::notice(); ?>

    <form method="post">
      <input type="radio" name="type" id="student" value="student" <?php View::checked('type', 'student'); ?> required />
        <label for="student">student</label>
      <input type="radio" name="type" id="recruiter" value="recruiter" <?php View::checked('type', 'recruiter'); ?> required />
        <label for="recruiter">recruiter</label>

      <div class="form-slider"><label for="email">Email</label><input type="text" id="email" name="email" value="<?php View::echof('email'); ?>" required /></div>

      <input type="submit" name="login" value="Login As" />
    </form>
  </div>
</panel>