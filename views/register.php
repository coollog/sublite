<?php require_once('view.php'); ?>

<panel class="form">
  <div class="content">
    <headline>Register as a Recruiter!</headline>
    <form method="post">
      <div class="form-slider"><label for="name">Name</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" /></div>
      <div class="form-slider"><label for="title">Job Title</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" /></div>
      <div class="form-slider"><label for="company">Company</label><input type="text" id="company" name="company" value="<?php vecho('company'); ?>" /></div>
      <div class="form-slider"><label for="email">Email</label><input type="text" id="email" name="email" value="<?php vecho('email'); ?>" /></div>
      <div class="form-slider"><label for="password">Password</label><input type="text" id="password" name="password" /></div>
      <div class="form-slider"><label for="confirm">Confirm Password</label><input type="text" id="confirm" name="confirm" /></div>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>