<style>
  .search {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    vertical-align: top;
  }
  .search form {
    height: 0;
    opacity: 0;
  }
  .collapse {
  }
</style>
<panel class="search">
  <?php View::notice(); ?>
  <form method="get">
    <div class="form-slider"><label for="location">Where do you want to sublet? </label><input type="text" id="location" name="location" value="<?php View::echof('location'); ?>" required /></div>

    <div class="sliderlabel">Max distance to search (mi): </div>
    <input type="hidden" id="proximity" name="proximity" value="<?php View::echof('proximity', null, 50); ?>" />
    <div class="slider slidermin" min="0" max="50" field="#proximity"></div>
    <div class="sliderafter" id="proximityafter"><span><?php View::echof('proximity', null, 50); ?></span> mi</div>

    <div class="form-slider form-half1"><label for="startdate">From: </label><input class="datepicker" type="text" id="startdate" name="startdate" value="<?php View::echof('startdate'); ?>" /></div>
    <div class="form-slider form-half2"><label for="enddate">To: </label><input class="datepicker" type="text" id="enddate" name="enddate" value="<?php View::echof('enddate'); ?>" /></div>

    <div class="sliderlabel">Price range ($ /month): </div>
    <input type="hidden" id="price0" name="price0" value="<?php View::echof('price0', null, 0); ?>" />
    <input type="hidden" id="price1" name="price1" value="<?php View::echof('price1', null, 5000); ?>" />
    <div class="slider sliderrange" min="0" max="10000" minfield="#price0" maxfield="#price1"></div>
    <div class="sliderafter" id="price0after">$<span><?php View::echof('price0', null, 0); ?></span> &ndash; $<span><?php View::echof('price1', null, 5000); ?></div>

    <div class="form-slider"><label for="occupancy">How many people? </label><input type="number" min="0" id="occupancy" name="occupancy" value="<?php View::echof('occupancy'); ?>" /></div>

    <?php View::partial('roomtype', array('any'=>true)); ?>

    <?php View::partial('buildingtype', array('any'=>true)); ?>

    <?php View::partial('amenities'); ?>

    <div class="form-slider"><label for="sortby">Sort By: </label>
      <select id="sortby" name="sortby" required>
        <?php
          $sortby = array(
            'priceIncreasing' => 'Cheapest',
            'priceDecreasing' => 'Most Expensive',
            'proximityIncreasing' => 'Closest'
          );

          if (isset($sortby[View::get('sortby')]))
            View::echof('sortby', '<option selected="selected" value="{var}">'.$sortby[View::get('sortby')].'</option>');
          foreach ($sortby as $val => $in) {
            echo "<option value=\"$val\">$in</option>";
          }
        ?>
      </select>
    </div>

    <?php View::notice(); ?>
    <input type="submit" name="search" value="Search" />
  </form>
  <input type="button" class="collapse" value="Show Filters" />
</panel>

<script>
  setTimeout(function() {
    $('.search form').css('height', 'auto').css('opacity', 1).hide();
  }, 500);
  $('.collapse').click(function () {
    scrollTo('.search');
    $('.search form').slideToggle(300, 'easeInOutCubic', function () {
      if ($($('.collapse')).val() == 'Show Filters')
        $($('.collapse')).val('Hide Filters');
      else
        $($('.collapse')).val('Show Filters');
    });
  });
</script>