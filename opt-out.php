<?php
  if (isset($_POST['email'])) {
    $emails =
      'qingyang.chen@yale.edu, tony.jiang@yale.edu, dean.li@yale.edu, info@sublite.net';
    if (!mail($emails, '[SubLite Bot] Opt-Out', $_POST['email'])) {
?>
  Failed to process email address. Please try again later or email
  'info@sublite.net'.
<?php
    } else {
?>
  You have successfully opted-out from receiving emails from us with email
  address '<?php echo $_POST['email']; ?>'!
<?php
    }
  }
?>

<form method="post">
  <h1>Opt-Out of Emails from SubLite</h1>
  Email Address: <input type="text" name="email" /><br />
  <input type="submit" value="Opt-Out" />
</form>