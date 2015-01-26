<!doctype html>
<html>
	<head>
		<link rel="icon" type="image/png" href="icon.png" />
		<?php
			if (isset($customheaders))
				echo $customheaders;
			else {
		?>
			<title>SubLite &ndash; An exclusive student subletting network.</title>
			<meta property="og:title" content="SubLite &ndash; An exclusive student subletting network." />
			<meta property="og:type" content="website" />
			<meta property="og:url" content="<?php echo getURL(); ?>" />
			<meta property="og:image" content="https://sublite.net/gfx/cover2.png" />
			<meta property="og:description" content="SubLite is an exclusive student-to-student platform for listing and discovering sublets to complement summer internships. Unlike other housing platforms, all our users are verified using their school email addresses." /> 
			<meta property="og:site_name" content="SubLite" />
		<?php
			}
		?>
		<meta name='description' content="SubLite is an exclusive student-to-student platform for listing and discovering sublets to complement summer internships. Unlike other housing platforms, all our users are verified using their school email addresses." />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/sunny/jquery-ui.css">
		<?php require_once('maincss.php'); ?>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		<!--<script src="//malsup.github.com/jquery.form.js"></script>-->
		<script src='main.js'></script>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-10510072-7', 'sublite.net');
			ga('send', 'pageview');
		</script>
	</head>
	<body>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=642157445814007";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>