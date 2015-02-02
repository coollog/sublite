<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <?php
      // Facebook meta tags
      if (vget('firstname')) { // is recruiter profile
        $name = $viewVars['firstname'] . ' '. $viewVars['lastname'];
        echo '<meta property="og:title" content="' . $name . ' on SubLite" />';
        $photo = vget('photo');
        if (strpos($photo, 'assets/gfx') !== FALSE)
          $photo = 'https://sublite.net/app/' . $photo;
        echo '<meta property="og:image" content="' . $photo . '" />';
        echo '<meta property="og:description" content="Check out ' . $name .
          '\'s profile on SubLite!" />';
      }
      elseif (vget('deadline')) { // is job listing
        $name = vget('companyname');
        $title = vget('title');
        echo '<meta property="og:title" content="' . $name . ' is hiring for the position of ' . $title . ' on SubLite!" />';
        $photo = vget('companybanner');
        if(!$photo) $photo = 'https://sublite.net/app/assets/gfx/defaultpic.png';
        echo '<meta property="og:image:secure_url" content="' . $photo . '" />';
        echo '<meta property="og:image" content="' . $photo . '" />';
        echo '<meta property="og:image:type" content="image/jpeg" />';
        list($width, $height) = getimagesize($photo);
        echo '<meta property="og:image:width" content="' . $width . '" />';
        echo '<meta property="og:image:height" content="' . $height . '" />';
        echo '<meta property="og:description" content="Apply to the ' . $title .
          ' position on SubLite!" />';
      }
      elseif (vget('industry')) { // is company profile
        $name = vget('name');
        echo '<meta property="og:title" content="Check out ' . $name . ' on SubLite!" />';
        $photo = vget('logophoto');
        if(!$photo) $photo = 'https://sublite.net/app/assets/gfx/defaultpic.png';
        echo '<meta property="og:image:secure_url" content="' . $photo . '" />';
        echo '<meta property="og:image" content="' . $photo . '" />';
        echo '<meta property="og:image:type" content="image/jpeg" />';
        list($width, $height) = getimagesize($photo);
        echo '<meta property="og:image:width" content="' . $width . '" />';
        echo '<meta property="og:image:height" content="' . $height . '" />';
        $desc = vget('desc');
        echo '<meta property="og:description" content="' . $desc . '" />';
      }
      elseif (strpos(htmlspecialchars($_SERVER['REQUEST_URI']), "/jobs") !== FALSE) { // on /jobs
    ?>
        <meta property="og:title" content="SubLite &ndash; Your One-Stop Shop for a Great Summer!" />
        <meta property="og:image" content="https://sublite.net/app/assets/gfx/studentmain.jpg" />
        <meta property="og:description" content="Attract the New Generation Talent with your Company's Unique Personality." />
        <meta property="og:image:width" content="1677" />
        <meta property="og:image:height" content="1118" />
    <?php
      }
      else { // default
    ?>
        <meta property="og:title" content="SubLite &ndash; Your One-Stop Shop for a Great Summer!" />
        <meta property="og:image" content="https://sublite.net/app/assets/gfx/studentmain.jpg" />
        <meta property="og:description" content="Find summer internships and safe, student-only summer housing with SubLite! Verify your &quot;.edu&quot; email address to get started! It's completely free!" />
        <meta property="og:image:width" content="1677" />
        <meta property="og:image:height" content="1118" />
    <?php
      }
      $url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
      $url .= $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
      echo '<meta property="og:url" content="' . $url . '" />';
    ?>
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="SubLite" />

    <title>SubLite &ndash; Your One-Stop Shop for a Great Summer!</title>
    <link rel="icon" type="image/png" href="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/favicon.png" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/jquery.slidinglabels.min.js"></script>


    <?php require_once($GLOBALS['dirpre'].'assets/js/formjs.php'); ?>
    <?php require_once($GLOBALS['dirpre'].'assets/css/maincss.php'); ?>
    <?php require_once($GLOBALS['dirpre'].'assets/css/formcss.php'); ?>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-10510072-7', 'auto');
      ga('send', 'pageview');

    </script>
  </head>
  <body>
    <?php require_once($GLOBALS['dirpre'].'views/navbar.php'); ?>
