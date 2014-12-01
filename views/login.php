<?php require_once('views/view.php'); ?>

<form method="post">
  <input type="email" name="email" placeholder="Email" value="<?php vecho('email'); ?>" />
  <input type="password" name="pass" placeholder="Password" />
  <input type="submit" name="login" value="Login" />
</form>