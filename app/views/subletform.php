<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Sublet Listing</headline>
    <form method="post">
      <?php 
        if (vget('_id') !== null) {
          $id = vget('_id');
          echo ' &nbsp; ' . vlinkto('<input type="button" value="View Sublet Listing" /><br /><br />', 'sublet', array('id' => $id), true);
        }
      ?>

      <?php vnotice(); ?>
      
      <div class="form-slider"><label for="address">Address:</label><input type="text" id="address" name="address" value="<?php vecho('address'); ?>" required /></div>
      <div class="form-slider"><label for="city">City:</label><input type="text" id="city" name="city" value="<?php vecho('city'); ?>" required /></div>
      <div class="form-slider"><label for="state">State:</label><input type="text" id="state" name="state" value="<?php vecho('state'); ?>" required /></div>

      <div class="form-slider"><label for="startdate">Available from (mm/dd/yyyy):</label><input type="text" id="startdate" name="startdate" value="<?php vecho('startdate'); ?>" required /></div>
      <div class="form-slider"><label for="enddate">Available till (mm/dd/yyyy):</label><input type="text" id="enddate" name="enddate" value="<?php vecho('enddate'); ?>" required /></div>

      <div class="form-slider"><label for="price">Price ($):</label><input type="text" id="price" name="price" value="<?php vecho('price'); ?>" required /></div>
      <right>
        <input type="radio" name="pricetype" id="month" value="month" <?php vchecked('pricetype', 'month'); ?> required /><label for="month"> / month</label>
        <input type="radio" name="pricetype" id="week" value="week" <?php vchecked('pricetype', 'week'); ?> required /><label for="week"> / week</label>
        <input type="radio" name="pricetype" id="day" value="day" <?php vchecked('pricetype', 'day'); ?> required /><label for="day"> / day</label>
      </right>

      <div class="form-slider"><label for="title">Title:</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" required maxlength="100" /></div>
      <div class="form-slider"><label for="summary" class="fortextarea">Summary (max 1000 chars): </label><textarea id="summary" name="summary" maxlength="1000"><?php vecho('summary'); ?></textarea></div>

      <div class="form-slider"><label for="occupancy">Max occupancy:</label><input type="text" id="occupancy" name="occupancy" value="<?php vecho('occupancy'); ?>" required /></div>

      <div class="form-slider">
        <label for="gender">Gender restriction: </label>
        <select id="gender" name="gender" required>
          <?php vecho('gender', '<option selected="selected">{var}</option>'); ?>
          <option value="both">All genders welcome</option>
          <option value="male">Male only</option>
          <option value="female">Female only</option>
        </select>
      </div>

      <?php vpartial('roomtype'); ?>

      <?php vpartial('buildingtype'); ?>

      <?php vpartial('amenities'); ?>

      <?php 
        vpartial('s3multiple', array(
          's3name' => 'photos', 
          's3title' => 'Photos (upload at least 4):',
          's3links' => vget('photos')
        ));
      ?>

      <?php if (vget('submitname') == 'edit') { ?>
        <div class="form-slider">
          <label for="fill">Publish:</label>
          <input type="hidden" id="fill" value="blah" />
          <left class="checkboxes">
            <input type="checkbox" name="publish" id="publish" value="true" <?php vchecked('publish', 'true'); ?> /> Make this listing public.
          </left>
        </div>
      <?php } ?>

      <br />
      <input type="checkbox" name="terms" id="terms" value="agree" required /> <label for="terms">I have read, fully understand, and agree to SubLite&rsquo;s <a href="terms.php">Terms of Service</a>. I represent and warrant that I have permission to list this property, and that the description is accurate and not misleading. I will negotiate the terms of the stay with potential guests in good-faith.</label>

      <?php vnotice(); ?>

      <right>
        <input type="submit" name="<?php vecho('submitname'); ?>" value="<?php vecho('submitvalue'); ?>" />
      </right>
    </form>
  </div>
</panel>

<script>
  formunloadmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>