<style>
  panel.main {
    height: 500px;
    position: relative;
    padding: 0;
    box-sizing: border-box;
    overflow-x: scroll;
    overflow-y: hidden;
    white-space: nowrap;
  }
    .photo {
      background: url('<?php vecho("mainphoto") ?>') no-repeat center center;
      background-size: cover;
      width: 100%;
      height: 100%;
      display: inline-block;
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
  }
    .subletinfo .title {
      font-size: 2em;
      line-height: 1.1em;
      font-weight: 700;
      max-width: 80%;
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
  .summary {
    margin-top: 60px;
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
    width: 75px;
    height: 75px;
    margin: 0 auto 10px auto;
    border-radius: 25px;
    background: transparent no-repeat center center;
    background-size: cover;
  }
  .studentname {

  }
  .studentschool {

  }

  panel.amenities {
    background: #fffefa;
  }
  .amenity {
    display: inline-block;
    text-align: center;
    width: 140px;
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


</style>

<panel class="main">
  <?php
    foreach (vget('photos') as $photo) {
  ?>
      <div class="photo" style="background-image: url('<?php echo $photo; ?>');"></div>
  <?php
    }
  ?>
</panel>

<panel class="info sublet">
  <div class="content">
    <table><tr>
      <td class="subletinfo">
        <div class="section1">
          <div class="price">
            $<?php vecho('price'); ?><small>/<?php vecho('pricetype'); ?></small>
          </div>
          <div class="title"><?php vecho('title'); ?></div>
          <div class="address"><?php vecho('address'); ?></div>
        </div>
        <div class="section2">
          <?php vecho('roomtype'); ?><br />
          <?php vecho('buildingtype'); ?><br />
          <?php vecho('gender'); ?><br />
          max <?php vecho('occupancy'); ?> people<br />
          anytime between <?php vecho('startdate'); ?> &ndash; <?php vecho('enddate'); ?>
        </div>
        <div class="summary">
          <subheadline>Summary</subheadline>
          <?php vecho('summary'); ?>
        </div>
      </td>
      <td class="studentinfo">
        <div class="studentprofile">
          <div class="studentpic" style="background-image: url('<?php vecho('studentpic'); ?>');"></div>
          <div class="studentname"><?php vecho('studentname'); ?></div>
          <div class="studentsschool">
            <?php vecho('studentschool'); ?><?php vecho('studentclass'); ?>
          </div>
          <input type="button" class="reverse" value="Contact Owner" />
        </div>
      </td>
    </tr></table>
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
    ?>
  </div>
</panel>
<panel class="comments">
  <div class="content">

  </div>
</panel>