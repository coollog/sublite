<?php
  class Metatags {
    public static function ogTitle($title) {
      echo "<meta property=\"og:title\" content=\"$title\" />";
    }
    public static function ogDescription($description) {
      echo "<meta property=\"og:description\" content=\"$description\" />";
    }
    public static function ogImage($image) {
      echo "<meta property=\"og:image\" content=\"$image\" />";
    }
    public static function ogImageSecureUrl($image) {
      echo "<meta property=\"og:image:secure_url\" content=\"$image\" />";
    }
    public static function ogImageType($type) {
      echo "<meta property=\"og:image:type\" content=\"$type\" />";
    }
    public static function ogImageWidth($width) {
      echo "<meta property=\"og:image:width\" content=\"$width\" />";
    }
    public static function ogImageHeight($height) {
      echo "<meta property=\"og:image:height\" content=\"$height\" />";
    }
    public static function title($title) {
      echo "<title>$title</title>";
    }
    public static function defaultImages() {
      self::ogImage("https://sublite.net/app/assets/gfx/studentmain.jpg");
      self::ogImage("https://sublite.net/app/assets/gfx/main.jpg");
      self::ogImage("https://sublite.s3.amazonaws.com/1423101952.jpeg");
      self::ogImageWidth(1000);
      self::ogImageHeight(1000);
    }
    public static function bothTitles($title) {
      self::title($title);
      self::ogTitle($title);
    }
  }

  switch (View::get('_metatagType')) {
    case 'sublet':
      $name = View::get('studentname');
      $city = View::get('city');
      $state = View::get('state');
      $title = View::get('title');
      $summary = View::get('summary');
      $school = View::get('studentschool');
      $photos = View::get('photos');

      Metatags::ogTitle("$title in $city, $state | $name, $school");
      Metatags::ogDescription($summary);
      foreach ($photos as $photourl) {
        Metatags::ogImage($photourl);
      }
      Metatags::title("$title on SubLite");
      break;

    case 'recruiter':
      $firstname = View::get('firstname');
      $lastname = View::get('lastname');
      $name = "$firstname $lastname";
      $photo = View::get('photo');
      if (strpos($photo, 'assets/gfx') !== false)
        $photo = 'https://sublite.net/app/' . $photo;

      Metatags::ogTitle("$name on SubLite");
      Metatags::ogImage($photo);
      Metatags::ogDescription("Check out $name's profile on SubLite!");
      Metatags::title("$name's recruiter profile on SubLite");
      break;

    case 'job':
      $name = View::get('companyname');
      $title = View::get('title');
      $photo = View::get('companybanner');
      if (!$photo) $photo = 'https://sublite.net/app/assets/gfx/defaultpic.png';
      list($width, $height) = getimagesize($photo);

      Metatags::ogTitle(
        "$name is hiring for the position of $title on SubLite!");
      Metatags::ogImageSecureUrl($photo);
      Metatags::ogImage($photo);
      Metatags::ogImageType('image/jpeg');
      Metatags::ogImageWidth($width);
      Metatags::ogImageHeight($height);
      Metatags::ogDescription("Apply to the $title position on SubLite!");
      Metatags::title("$title at $name &ndash; SubLite");
      break;

    case 'companyprofile':
      $name = View::get('name');
      $photo = View::get('logophoto');
      if(!$photo) $photo = 'https://sublite.net/app/assets/gfx/defaultpic.png';
      list($width, $height) = getimagesize($photo);
      $desc = View::get('desc');

      Metatags::ogTitle("Check out $name on SubLite!");
      Metatags::ogImageSecureUrl($photo);
      Metatags::ogImage($photo);
      Metatags::ogImageType('image/jpeg');
      Metatags::ogImageWidth($width);
      Metatags::ogImageHeight($height);
      Metatags::ogDescription($desc);
      Metatags::title("$name &ndash; Company profile on SubLite");
      break;

    case '/employers':
      Metatags::ogTitle(
        "SubLite &ndash; Your One-Stop Shop for a Great Summer!");
      Metatags::ogImage("https://sublite.net/app/assets/gfx/studentmain.jpg");
      Metatags::ogDescription(
        "Attract the New Generation Talent with your Company's Unique Personality.");
      Metatags::ogImageWidth(1677);
      Metatags::ogImageHeight(1118);
      break;

    case 'hubs':
      $title =
        "SubLite &ndash; Meet and Socialize with Students Working in Your City";
      Metatags::bothTitles($title);
      Metatags::ogImage("https://sublite.net/app/assets/gfx/socialmain.jpg");
      Metatags::ogDescription(
        "Get your questions answered and make new friends this summer!");
      Metatags::ogImageWidth(1677);
      Metatags::ogImageHeight(1118);
      break;

    case 'searchhousing':
      $data = View::get('data');
      $location = '';
      if ($data && isset($data['location']))
        $location = " - $data[location]";

      $title =
        "SubLite &ndash; Search for Sublets, Rentals, and Other Housing$location";
      Metatags::bothTitles($title);
      Metatags::defaultImages();
      break;

    case 'searchjobs':
      $title = "SubLite &ndash; Search for Jobs and Internships";
      Metatags::bothTitles($title);
      Metatags::defaultImages();
      break;

    default:
      $title = "SubLite &ndash; Your One-Stop Shop for a Great Summer!";
      Metatags::bothTitles($title);
      Metatags::defaultImages();
      Metatags::ogDescription(
        "Find summer internships and safe, student-only summer housing with " .
        "SubLite! Verify your &quot;.edu&quot; email address to get started! " .
        "It's completely free!"
      );
  }
?>