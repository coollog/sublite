<?php
  if (isset($_POST['email'])) {
    mail($email, '[SubLite Bot] Opt-Out', $_POST['email']);
?>
  You have successfully opted-out from receiving emails from us with email
  address '<?php echo $_POST['email']; ?>'!
<?php
  }
?>

<form method="post">
  <h1>Opt-Out of Emails from SubLite</h1>
  Email Address: <input type="text" name="email" /><br />
  <input type="submit" value="Opt-Out" />
</form>