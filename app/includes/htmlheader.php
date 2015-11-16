<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <?php View::partial('metatags', View::vars()); ?>
    <?php
      $url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
      $url .= $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
      echo '<meta property="og:url" content="' . $url . '" />';
    ?>

    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="SubLite" />
    <meta property="fb:app_id" content="478408982286879"/>

    <link rel="shortcut icon" type="image/png"
          href="data:<?php echo $GLOBALS['dirpre']; ?>assets/gfx/favicon.png" />

    <!-- JQUERY -->
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>-->
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/delete/jquery-1.11.3.min.js"></script>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/delete/jquery-ui.min.js"></script>
    <?php require_once($dirpreOrig.'assets/jqueryui/jquery-uimincss.php'); ?>

    <!-- STRIPE -->
    <script src="https://js.stripe.com/v2"></script>

    <!-- PLUGINS -->
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/jquery.slidinglabels.min.js"></script>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/jquery.timepicker.min.js"></script>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/jquery.form.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['dirpre']; ?>assets/css/jquery.timepicker.css">

    <?php require_once($dirpreOrig.'assets/js/mainjs.php'); ?>
    <?php require_once($dirpreOrig.'assets/js/formjs.php'); ?>
    <?php require_once($dirpreOrig.'assets/css/maincss.php'); ?>
    <?php require_once($dirpreOrig.'assets/css/formcss.php'); ?>
    <?php require_once($dirpreOrig.'assets/css/responsivecss.php'); ?>

    <?php if (!checkAdmin()) { // if isn't admin, do analytics ?>
      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-10510072-7', 'auto');
        ga('send', 'pageview');

      </script>
    <?php } ?>
  </head>
  <body>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/fbinit.js"></script>

    <?php require_once($dirpreOrig.'views/navbar.php'); ?>