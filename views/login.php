<panel class="form">
  <div class="content">
    <headline>Log In</headline>
    <form method="post">
      <div class="form-slider"><label for="email">Email</label><input type="text" id="email" name="email" value="<?php vecho('email'); ?>" /></div>
      <div class="form-slider"><label for="password">Password</label><input type="text" id="password" name="password" /></div>
      <?php vnotice(); ?>
      <input type="submit" name="login" value="Log In" />
    </form>
  </div>
</panel>