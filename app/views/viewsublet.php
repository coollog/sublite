<style>
  panel.main {
    height: 500px;
    position: relative;
    padding: 0;
    box-sizing: border-box;
    overflow: hidden;
    white-space: nowrap;
  }
    .photo {
      background: transparent no-repeat center center;
      background-size: cover;
      width: 100%;
      height: 100%;
      position: absolute;
      cursor: pointer;
      transition: 0.5s all ease-in-out;
    }
    .photocontrol {
      opacity: 0.5;
      background: transparent no-repeat center center;
      background-size: contain;
      width: 100px;
      position: absolute;
      height: 100%;
      cursor: pointer;
      transition: 0.1s all ease-in-out;
    }
    .photocontrol:hover {
      opacity: 0.8;
      background-color: rgba(0, 0, 0, 0.8);
    }
      .photocontrolleft {
        background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/left.png');
        left: 0;
        display: none;
      }
      .photocontrolright {
        background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/right.png');
        right: 0;
      }
  panel .content {
    text-align: left;
  }
  panel.sublet {
    padding-top: 0;
  }
  subheadline {
    margin-bottom: 1em;
    color: #000;
  }
  .subletinfo {
    padding: 20px 20px 0 0;
    position: relative;
    vertical-align: top;
  }
    .subletinfo .title {
      font-size: 2em;
      line-height: 1.1em;
      font-weight: 700;
      max-width: 75%;
    }
    .subletinfo .address {
      
    }
    .subletinfo .price {
      display: inline-block;
      float: right;
      font-size: 3em;
      line-height: 1.1em;
      letter-spacing: -1px;
      font-weight: 600;
    }
    .subletinfo .price small {
      font-size: 0.5em;
      font-weight: 400;
      opacity: 0.4;
    }
  .section2 {
    padding: 20px;
  }
  .details {
    position: relative;
  }
    .detail {
      display: inline-block;
      text-align: center;
      margin: 0 0%;
      font-size: 0.8em;
      height: 80px;
    }
    .detail table {
      width: 100%;
    }
      .detailpng {
        background: transparent no-repeat center center;
        background-size: 80px 80px;
        height: 40px;
        width: 60px;
      }
      .detailname {
        width: 80px;
      }
  .summary {
    background: #fafaf8;
    text-align: left;
    margin-top: -40px;
    padding: 40px 0 60px 0;
  }
  .studentinfo{
    width: 200px;
    text-align: center;
    vertical-align: top;
  }
    .studentprofile {
      background: #ffd800;
      padding: 20px;
      box-sizing: border-box;
    }
    .studentprofile input {
      margin-top: 20px;
      padding: 0 20px;
      text-transform: none;
      font-weight: 400;
    }
  .studentpic {
    width: 100px;
    height: 100px;
    margin: 0 auto 10px auto;
    border-radius: 50px;
    background: transparent no-repeat center center;
    background-size: cover;
  }
  .studentname {

  }
  .studentschool {

  }

  panel.amenities {
  }
  .amenity {
    display: inline-block;
    text-align: center;
    width: 140px;
    height: 140px;
  }
  .amenity table {
    width: 100%;
  }
    .amenitypng {
      background: transparent no-repeat center center;
      background-size: contain;
      height: 90px;
      width: 90px;
    }

  panel.comments {
    background: #fafaf8;
  }

  .pop, .popshare {
    position: fixed;
    left: 0;
    top: 0;
    margin: 0; padding: 0;
    z-index: 999;
    background: rgba(0,0,0,0.8);
    display: none;
    width: 100%;
    height: 100%;
  }
  .poptable {
    width: 100%;
    height: 100%;
    display: table;
    text-align: center;
  }
  .popphoto, .popcell {
    display: table-cell;
    vertical-align: middle;
    z-index: 999;
  }
  .popphoto img {
    max-width: 90vw;
    max-height: 90vh;
  }
  .popsharetext {
    max-width: 100vw;
    width: 600px;
    padding: 50px;
    background: #fff;
  }
  copy {
    display: block;
    font-weight: bold;
  }
</style>

<script>
  var curPhoto = 0;

  function showPhoto(index) {
    $('.photo').each(function() {
      var n = $(this).attr('index');
      $(this).css('left', (n - index) * 100 + "%");
    });
    if (index == 0)
      $('.photocontrolleft').hide();
    if (index > 0)
      $('.photocontrolleft').show();
    if (index < $('.photo').length - 1)
      $('.photocontrolright').show();
    if (index == $('.photo').length - 1)
      $('.photocontrolright').hide();

    curPhoto = index;
  }
  function showPhotoLeft() { showPhoto(curPhoto - 1); }
  function showPhotoRight() { showPhoto(curPhoto + 1); }

  function popPhoto(photo) {
    $('.pop').fadeIn(200, 'easeInOutCubic');
    $('.popphoto img').attr('src', photo);
  }

  function showShare() {
    if (!localStorage.hideShare) {
      $('.popshare').fadeIn(200, 'easeInOutCubic');
    }
  }
  function hideShare(hide) {
    localStorage.hideShare = hide;
    $('.popshare').fadeOut(100, 'easeInOutCubic');
  }

  $(function() {
    <?php if (vget('mine')) echo 'showShare();'; ?>

    showPhoto(0);
    $('.photocontrolleft').click(function() { showPhotoLeft(); });
    $('.photocontrolright').click(function() { showPhotoRight(); });

    $('.photo').click(function() {
      popPhoto($(this).attr('photo'));
    });
    $('.pop').click(function() { $(this).fadeOut(100, 'easeInOutCubic'); });

    <?php if (vget('commented')) {?>
      scrollTo('.comments');
    <?php } ?>
  });
</script>

<panel class="main">
  <?php
    $i = 0;
    foreach (vget('photos') as $photo) {
  ?>
      <div class="photo" index="<?php echo $i; ?>" style="background-image: url('<?php echo $photo; ?>');" photo="<?php echo $photo; ?>"></div>
  <?php
      $i ++;
    }
  ?>
  <div class="photocontrol photocontrolleft"></div>
  <div class="photocontrol photocontrolright"></div>
</panel>

<panel class="info sublet">
  <div class="content">
    <table style="width: 100%;"><tr>
      <td class="subletinfo">

        <div class="section1">
          <div class="price">
            $<?php vecho('price'); ?><small>/<?php vecho('pricetype'); ?></small>
          </div>
          <div class="title"><?php vecho('title'); ?></div>
          <div class="address"><?php vecho('address'); ?></div>
        </div>

        <div class="section2">
          <div style="float: right;">
            <?php vpartial('fb', array('route' => 'housing/sublet.php?id='.vget('_id'))); ?>
          </div>
          <div class="details">
            <div class="detail"><table>
              <tr><td class="detailpng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/room.png');"></td></tr>
              <tr><td class="detailname"><?php vecho('roomtype'); ?></td></tr>
            </table></div>
            <div class="detail"><table>
              <tr><td class="detailpng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/house.png');"></td></tr>
              <tr><td class="detailname"><?php vecho('buildingtype'); ?></td></tr>
            </table></div>
            <?php if (strlen(vget('gender')) > 0) { ?>
              <div class="detail"><table>
                <tr><td class="detailpng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/gender.png'); background-size: 50px 50px;"></td></tr>
                <tr><td class="detailname"><?php vecho('gender'); ?></td></tr>
              </table></div>
            <?php } ?>
            <div class="detail"><table>
              <tr><td class="detailpng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/guest.png');"></td></tr>
              <tr><td class="detailname">max <?php vecho('occupancy'); ?> people</td></tr>
            </table></div>
            <div class="detail"><table>
              <tr>
                <td class="detailpng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/calendar.png');"></td>
                <td rowspan="2" style="width: auto; position: relative; left: -10px; text-align: left;">
                  anytime between
                  <div style="font-size: 1.5em; letter-spacing: -1px;">
                    <strong><?php vecho('startdate'); ?></strong> &ndash; <strong><?php vecho('enddate'); ?></strong></normal>
                  </div>
                </td>
              </tr>
              <tr><td class="detailname">available</td></tr>
            </table></div>
          </div>
        </div>
      </td>

      <td class="studentinfo">
        <div class="studentprofile">
          <div class="studentpic" style="background-image: url('<?php vecho('studentpic'); ?>');"></div>
          <div class="studentname"><?php vecho('studentname'); ?></div>
          <div class="studentschool">
            <?php vecho('studentcollege'); ?><?php vecho('studentclass'); ?>
          </div>
          <?php if(!vget('Loggedinstudent')) { ?>
            <br /><i>You must <a href="../login.php">login</a> or <a href="../register.php">register</a> to contact the owner.</i>
          <?php } else { ?>
            <a href="newmessage.php?from=<?php vecho('L_id'); ?>&to=<?php vecho('studentid'); ?>&msg=<?php vecho('studentmsg'); ?>" onClick="return confirm('I have read, fully understand, and agree to Sublite’s Terms of Service and Privacy Policy. I agree to contact the owner in good-faith to inquire about the listing.')">
              <input type="button" class="reverse" value="Contact Owner" />
            </a>
          <?php } ?>
        </div>
      </td>

    </tr></table>
  </div>
</panel>

<panel class="summary">
  <div class="content">
    <subheadline>Summary</subheadline>
    <?php echo nl2br(vget('summary')); ?>
  </div>
</panel>

<panel class="amenities">
  <div class="content">
    <subheadline>Amenities</subheadline>
    <?php
      $amenities = array(
        "In-Building Gym" => "gym",
        "Free Parking" => "carpark",
        "Reserved Parking (Additional Cost)" => "carpark",
        "Pool" => "pool",
        "Rooftop Access" => "rooftop",
        "Yard" => "fence",
        "In-Building Mailboxes" => "mail",
        "Laundry Machines" => "laundry",
        "Wi-Fi" => "wifi",
        "Cable" => "cable",
        "Wheelchair Accessibility" => "handicap",
        "Sports Fields" => "ball"
      );
      $amenitiesn = 0;
      foreach (vget('amenities') as $amenity) {
        if (!isset($amenities[$amenity])) continue;
        $png = $amenities[$amenity];
    ?>
        <div class="amenity">
          <table>
            <tr><td class="amenitypng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/<?php echo $png; ?>.png');"></td></tr>
            <tr><td class="amenityname"><?php echo $amenity; ?></td></tr>
          </table>
        </div>
    <?php
        $amenitiesn ++;
      }
      if ($amenitiesn == 0) {
    ?>
        <i>No amenities reported.</i>
    <?php
      }
    ?>
  </div>
</panel>
<panel class="comments">
  <div class="content">
    <subheadline>Comments</subheadline>
    <form method="post">
      <?php if (count(vget('comments')) == 0) { ?>
        <i>No comments so far. Be the first to comment!</i><br />
      <?php } else { ?>

        <style>
          .comment {
            border-bottom: 1px solid #eee;
            padding: 20px;
          }
          .comment:last-of-type {
            border-bottom: 0;
            padding-bottom: 0;
          }
          table.commentblock {
            display: table;
            width: 100%;
          }
          table.commentblock td {
            vertical-align: top;
          }
          table.commentblock profpic {
            width: 80px;
            height: 80px;
            border-radius: 40px;
          }
          table.commentblock .pp {
            width: 80px;
          }
          profpic {
            background: transparent no-repeat center center;
            background-size: cover;
            display: block;
          }
          name {
            font-size: 1.2em;
            color: #035d75;
            font-weight: 700;
          }
          time {
            opacity: 0.5;
            margin-left: 1em;
          }
          data {
            display: block;
            margin-left: 2em;
          }
          text {
            display: block;
          }
        </style>
        <?php
          foreach (vget('comments') as $comment) {
            extract($comment);
        ?>
          <div class="comment">
            <table class="commentblock"><tr>
              <td class="pp"><profpic style="background-image: url('<?php echo $photo ?>');"></profpic></td>
              <td><data>
                <name><?php echo $name; ?></name><time><?php echo $time ?></time>
                <text><?php echo $text; ?></text>
              </data></td>
            </tr></table>
          </div>
        <?php } ?>

      <?php } ?>
      <br />
      <?php if(!vget('Loggedinstudent')) { ?>
        <i>You must <a href="../login.php">login</a> or <a href="../register.php">register</a> to comment.</i>
      <?php } else { ?>

        <textarea id="comment" name="comment" required maxlength="2000" placeholder="Write Your Comment:"><?php vecho('comment'); ?></textarea>
        <?php vnotice(); ?>
        <right>
          <?php vpartial('fb', array('route' => 'housing/sublet.php?id='.vget('_id'))); ?>
          &nbsp; <input type="submit" name="addcomment" value="Comment" />
        </right>

      <?php } ?>
    </form>
  </div>
</panel>
<panel class="map" style="padding: 0;">
  <style type="text/css">
    #map-canvas{
      height: 300px;
      width: 100%;
    }
  </style>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDORLARDVNHaHBSLZ0UG-1EGABk-IH2uq0&sensor=false"></script>
  <script type="text/javascript">
    function initialize() {
      var myLatlng = new google.maps.LatLng(<?php vecho('latitude'); ?>, <?php vecho('longitude'); ?>);

      var styles = [
        {
          stylers: [
            { hue: "#035d75" },
            { saturation: -10 }
          ]
        },{
          featureType: "road",
          elementType: "geometry",
          stylers: [
            { lightness: 10},
            { visibility: "simplified" }
          ]
        },{
          featureType: "road.local",
          elementType: "labels",
          stylers: [
            { visibility: "off" }
          ]
        }
      ];

      var mapOptions = {
        center: myLatlng,
        /*adjust number to change starting zoom size*/
        zoom: 15,
        styles: styles
      };

      var map = new google.maps.Map(document.getElementById('map-canvas'),
          mapOptions);

      //set icon for marker
      var houseicon = '<?php echo $GLOBALS['dirpre']; ?>assets/gfx/map/marker.png';

      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: houseicon
      });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
  </script>
  <div id="map-canvas"></div>
</panel>

<div class="pop">
  <div class="poptable">
    <div class="popphoto"><img src="" /></div>
  </div>
</div>
<div class="popshare">
  <div class="poptable">
    <div class="popcell">
      <div class="popsharetext">
        Share your listing on social media such as Facebook groups to advertise your listing! Copy and paste the link below into posts:
        <copy>www.sublite.net/housing/sublet.php?id=<?php vecho('_id'); ?></copy>

        or Like and Share below:
        <?php vpartial('fb', array('route' => 'housing/sublet.php?id='.vget('_id'))); ?>
        <br />
        <input type="button" value="Sounds good!" />
        <input type="button" value="Don't show this again." />
      </div>
    </div>
  </div>
</div>