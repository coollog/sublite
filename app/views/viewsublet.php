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

  .pop {
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
  .popphoto {
    display: table-cell;
    vertical-align: middle;
    z-index: 999;
  }
  .popphoto img {
    max-width: 90vw;
    max-height: 90vh;
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

  $(function() {
    showPhoto(0);
    $('.photocontrolleft').click(function() { showPhotoLeft(); });
    $('.photocontrolright').click(function() { showPhotoRight(); });

    $('.photo').click(function() {
      popPhoto($(this).attr('photo'));
    });
    $('.pop').click(function() { $(this).fadeOut(100, 'easeInOutCubic'); });
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
            <style>
              iframe {
                margin: 0;
              }
            </style>
            <div class="fb-like" data-href="https://sublite.net/housing/sublet.php?id=<?php vecho('_id'); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" style="margin: 0; display: block;"></div>
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
    <?php vecho('summary'); ?>
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
      foreach (vget('amenities') as $amenity) {
        $png = $amenities[$amenity];
    ?>
        <div class="amenity">
          <table>
            <tr><td class="amenitypng" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/<?php echo $png; ?>.png');"></td></tr>
            <tr><td class="amenityname"><?php echo $amenity; ?></td></tr>
          </table>
        </div>
    <?php  
      }
      if (count(vget('amenities')) == 0) {
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
    <i>No comments so far. Be the first to comment!</i>
  </div>
</panel>

<div class="pop">
  <div class="poptable">
    <div class="popphoto"><img src="" /></div>
  </div>
</div>