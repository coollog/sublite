<panel class="form">
  <div class="content">
    <headline><?php View::echof('headline'); ?> Sublet Listing</headline>
    <form method="post">
      <?php
        if (View::get('_id') !== null) {
          $id = View::get('_id');
          echo ' &nbsp; ' . View::linkTo('<input type="button" value="View Sublet Listing" /><br /><br />', 'sublet', array('id' => $id), true);
        }
      ?>

      <?php View::notice(); ?>

      <div class="form-slider"><label for="address">Address:</label><input type="text" id="address" name="address" value="<?php View::echof('address'); ?>" required /></div>
      <div class="form-slider"><label for="city">City:</label><input type="text" id="city" name="city" value="<?php View::echof('city'); ?>" required /></div>
      <div class="form-slider"><label for="state">State:</label><input type="text" id="state" name="state" value="<?php View::echof('state'); ?>" required /></div>

      <div class="form-slider"><label for="startdate">Available from (mm/dd/yyyy):</label><input class="datepicker" type="text" id="startdate" name="startdate" value="<?php View::echof('startdate'); ?>" required /></div>
      <div class="form-slider"><label for="enddate">Available till (mm/dd/yyyy):</label><input class="datepicker" type="text" id="enddate" name="enddate" value="<?php View::echof('enddate'); ?>" required /></div>

      <div class="form-slider"><label for="price">Price ($):</label><input type="text" id="price" name="price" value="<?php View::echof('price'); ?>" required /></div>
      <right>
        <input type="radio" name="pricetype" id="month" value="month" <?php View::checked('pricetype', 'month'); ?> required /><label for="month"> / month</label>
        <input type="radio" name="pricetype" id="week" value="week" <?php View::checked('pricetype', 'week'); ?> required /><label for="week"> / week</label>
        <input type="radio" name="pricetype" id="day" value="day" <?php View::checked('pricetype', 'day'); ?> required /><label for="day"> / day</label>
      </right>

      <div class="form-slider"><label for="title">Title:</label><input type="text" id="title" name="title" value="<?php View::echof('title'); ?>" required maxlength="100" /></div>
      <div class="form-slider"><label for="summary" class="fortextarea">Summary (max 1000 chars): </label><textarea id="summary" name="summary" maxlength="1000"><?php View::echof('summary'); ?></textarea></div>

      <div class="form-slider"><label for="occupancy">Max occupancy:</label><input type="text" id="occupancy" name="occupancy" value="<?php View::echof('occupancy'); ?>" required /></div>

      <div class="form-slider">
        <label for="gender">Gender restriction: </label>
        <select id="gender" name="gender" required>
          <?php View::echof('gender', '<option selected="selected">{var}</option>'); ?>
          <option value="both">All genders welcome</option>
          <option value="male">Male only</option>
          <option value="female">Female only</option>
        </select>
      </div>

      <?php View::partial('roomtype'); ?>

      <?php View::partial('buildingtype'); ?>

      <?php View::partial('amenities'); ?>

      <?php
        View::partial('s3multiple', array(
          's3name' => 'photos',
          's3title' => 'Photos (recommended: >4 photos):',
          's3links' => View::get('photos')
        ));
      ?>

      <?php if (View::get('submitname') == 'edit') { ?>
        <div class="form-slider">
          <label for="fill">Publish:</label>
          <input type="hidden" id="fill" value="blah" />
          <left class="checkboxes">
            <input type="checkbox" name="publish" id="publish" value="true" <?php View::checked('publish', 'true'); ?> /> Make this listing public.
          </left>
        </div>
      <?php } ?>

      <br />
      <input type="checkbox" name="terms" id="terms" value="agree" required /> <label for="terms">I have read, fully understand, and agree to SubLite&rsquo;s <a href="terms.php">Terms of Service</a>. I represent and warrant that I have permission to list this property, and that the description is accurate and not misleading. I will negotiate the terms of the stay with potential guests in good-faith.</label>

      <br /><br />

      <?php View::notice(); ?>

      <right>
        <i>Remember to share your listing on social media such as Facebook!</i>
        <input type="submit" name="<?php View::echof('submitname'); ?>" value="<?php View::echof('submitvalue'); ?>" />
      </right>
    </form>
  </div>
</panel>

<script>
  formunloadmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>