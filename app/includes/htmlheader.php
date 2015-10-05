<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <?php
      // Facebook meta tags
      if (vget('price')) { // is sublet
        $name = vget('studentname');
        $city = vget('city');
        $state = vget('state');
        $title = vget('title');
        $summary = vget('summary');
        $school = vget('studentschool');
        echo '<meta property="og:title" content="' . $title . ' in ' . $city .
          ', ' . $state . ' | ' . $name . ', ' . $school . '" />';
        echo '<meta property="og:description" content="' . $summary . '" />';
        foreach(vget('photos') as $photourl) {
          echo '<meta property="og:image" content="' . $photourl . '" />';
        }
        echo '<title>' . $title . ' on SubLite</title>';
      }
      elseif (vget('firstname')) { // is recruiter profile
        $name = $viewVars['firstname'] . ' '. $viewVars['lastname'];
        echo '<meta property="og:title" content="' . $name . ' on SubLite" />';
        $photo = vget('photo');
        if (strpos($photo, 'assets/gfx') !== FALSE)
          $photo = 'https://sublite.net/app/' . $photo;
        echo '<meta property="og:image" content="' . $photo . '" />';
        echo '<meta property="og:description" content="Check out ' . $name .
          '\'s profile on SubLite!" />';
        echo "<title>" . $name . "'s recruiter profile on SubLite</title>";
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
        echo '<title>' . $title . ' at ' . $name . ' &ndash; SubLite</title>';
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
        echo "<title>" . $name . " &ndash; Company profile on SubLite</title>";
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
      else if (vget('hubs')) {
        $title = "SubLite &ndash; Meet and Socialize with Students Working in Your City";
    ?>
        <meta property="og:title" content="<?php echo $title; ?>" />
        <meta property="og:image" content="https://sublite.net/app/assets/gfx/socialmain.jpg" />
        <meta property="og:description" content="Get your questions answered and make new friends this summer!" />
        <meta property="og:image:width" content="1677" />
        <meta property="og:image:height" content="1118" />
        <title><?php echo $title; ?></title>
    <?php
      } else { // default
    ?>
        <meta property="og:title" content="SubLite &ndash; Your One-Stop Shop for a Great Summer!" />
        <meta property="og:image" content="https://sublite.net/app/assets/gfx/studentmain.jpg" />
        <meta property="og:image" content="https://sublite.net/app/assets/gfx/main.jpg" />
        <meta property="og:image" content="https://sublite.s3.amazonaws.com/1423101952.jpeg" />
        <meta property="og:description" content="Find summer internships and safe, student-only summer housing with SubLite! Verify your &quot;.edu&quot; email address to get started! It's completely free!" />
        <meta property="og:image:width" content="1000" />
        <meta property="og:image:height" content="1000" />
    <?php
      }
      $url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
      $url .= $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
      echo '<meta property="og:url" content="' . $url . '" />';
    ?>
    <?php
      if (vget('search')) {
        if (vget('search') == 'housing') {
          $data = vget('data');
    ?>
          <title>
            SubLite &ndash; Search for Sublets, Rentals, and Other Housing
            <?php if ($data) echo ' - '.$data['location']; ?>
          </title>
    <?php
        }
        if (vget('search') == 'jobs') {
    ?>
          <title>SubLite &ndash; Search for Jobs and Internships</title>
    <?php
        }
      } else {
    ?>
        <title>SubLite &ndash; Find internships and housing and sublets for rent!</title>
    <?php
      }
    ?>

    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="SubLite" />

    <link rel="icon" type="image/png" href="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/favicon.png" />

    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>-->
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/delete/jquery-1.11.3.min.js"></script>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/delete/jquery-ui.min.js"></script>
    <?php require_once($dirpreOrig.'assets/jqueryui/jquery-uimincss.php'); ?>

    <!-- PLUGINS -->
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/jquery.slidinglabels.min.js"></script>
    <script src="<?php echo $GLOBALS['dirpre']; ?>assets/js/jquery.timepicker.min.js"></script>
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

    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '478408982286879',
          xfbml      : true,
          version    : 'v2.1'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>

    <?php require_once($dirpreOrig.'views/navbar.php'); ?>
