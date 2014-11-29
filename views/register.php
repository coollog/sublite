<?php require_once('view.php'); ?>

<form method="post">
  <input type="email" name="email" placeholder="Email" value="<?php vecho('email'); ?>" />
  <input type="password" name="pass" placeholder="Password" />
  <input type="text" name="firstname" placeholder="First Name" value="<?php vecho('firstname'); ?>" />
  <input type="text" name="lastname" placeholder="Last Name" value="<?php vecho('lastname'); ?>" />
  <input type="text" name="company" placeholder="Company" value="<?php vecho('company'); ?>" />
  <input type="text" name="title" placeholder="Title" value="<?php vecho('title'); ?>" />
  <input type="text" name="phone" placeholder="Phone" value="<?php vecho('phone'); ?>" />
  <input type="submit" name="register" value="Register" />
</form>