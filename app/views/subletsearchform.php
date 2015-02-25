<panel class="search">
  <div class="content">
    <form method="post">
      <div class="form-slider"><label for="location">Where do you want to sublet? </label><input type="text" id="location" name="location" value="<?php vecho('location'); ?>" required /></div>

      <div class="form-slider"><label for="proximity">Proximity: </label><input type="text" id="proximity" name="proximity" value="<?php vecho('proximity'); ?>" /></div>

      <div class="form-slider"><label for="startdate">From: </label><input type="text" id="startdate" name="startdate" value="<?php vecho('startdate'); ?>" /></div>
      <div class="form-slider"><label for="enddate">To: </label><input type="text" id="enddate" name="enddate" value="<?php vecho('enddate'); ?>" /></div>

      <div class="form-slider"><label for="price0">Min Price: </label><input type="text" id="price0" name="price0" value="<?php vecho('price0'); ?>" /></div>
      <div class="form-slider"><label for="price1">Max Price: </label><input type="text" id="price1" name="price1" value="<?php vecho('price1'); ?>" /></div>

      <div class="form-slider"><label for="occupancy">How many people? </label><input type="text" id="occupancy" name="occupancy" value="<?php vecho('occupancy'); ?>" /></div>

      <div class="form-slider"><label for="sortby">Sort By: </label><input type="text" id="sortby" name="sortby" value="<?php vecho('sortby'); ?>" /></div>

      <?php vpartial('roomtype', array('any'=>true)); ?>

      <?php vpartial('buildingtype', array('any'=>true)); ?>

      <?php vpartial('amenities'); ?>
      
      <?php vnotice(); ?>
      <input type="submit" name="search" value="Search" />
    </form>
  </div>
</panel>