<style>
  .search {
    width: 24%;
    display: inline-block;
    box-sizing: border-box;
    vertical-align: top;
    float: left;
  }

  .form-slider label {
    position: static !important;
    display: block !important;
    text-align: left;
  }
  .sliderlabel {
    /*text-align: left;*/
  }

  @media (min-width: 1000px) {
    .form-half1, .form-half2 {
      width: 100%;
    }
  }
  @media (max-width: 1000px) {
    .search {
      display: block;
      width: 100%;
    }
  }
</style>
<panel class="search">
  <form method="post">
    <div class="form-slider"><label for="location">Where do you want to sublet? </label><input type="text" id="location" name="location" value="<?php vecho('location'); ?>" required /></div>

    <div class="sliderlabel">Max distance to search (mi): </div>
    <input type="hidden" id="proximity" name="proximity" value="<?php vecho('proximity', null, 50); ?>" />
    <div class="slider slidermin" min="0" max="50" field="#proximity"></div>
    <div class="sliderafter" id="proximityafter"><span><?php vecho('proximity', null, 50); ?></span> mi</div>

    <div class="form-slider form-half1"><label for="startdate">From: </label><input class="datepicker" type="text" id="startdate" name="startdate" value="<?php vecho('startdate'); ?>" /></div>
    <div class="form-slider form-half2"><label for="enddate">To: </label><input class="datepicker" type="text" id="enddate" name="enddate" value="<?php vecho('enddate'); ?>" /></div>

    <div class="sliderlabel">Price range ($ /month): </div>
    <input type="hidden" id="price0" name="price0" value="<?php vecho('price0', null, 0); ?>" />
    <input type="hidden" id="price1" name="price1" value="<?php vecho('price1', null, 5000); ?>" />
    <div class="slider sliderrange" min="0" max="10000" minfield="#price0" maxfield="#price1"></div>
    <div class="sliderafter" id="price0after">$<span><?php vecho('price0', null, 0); ?></span> &ndash; $<span><?php vecho('price1', null, 5000); ?></div>

    <div class="form-slider"><label for="occupancy">How many people? </label><input type="number" id="occupancy" name="occupancy" value="<?php vecho('occupancy'); ?>" /></div>

    <?php vpartial('roomtype', array('any'=>true)); ?>

    <?php vpartial('buildingtype', array('any'=>true)); ?>

    <?php vpartial('amenities'); ?>

    <div class="form-slider"><label for="sortby">Sort By: </label>
      <select id="sortby" name="sortby" required>
        <?php
          $sortby = array(
            'priceIncreasing' => 'Cheapest',
            'priceDecreasing' => 'Most Expensive',
            'proximityIncreasing' => 'Closest'
          );

          if (isset($sortby[vget('sortby')]))
            vecho('sortby', '<option selected="selected" value="{var}">'.$sortby[vget('sortby')].'</option>');
          foreach ($sortby as $val => $in) {
            echo "<option value=\"$val\">$in</option>";
          }
        ?>
      </select>
    </div>
    
    <?php vnotice(); ?>
    <input type="submit" name="search" value="Search" />
  </form>
</panel>