<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Job Listing</headline>
    <form method="post">
      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" /></div>
      <div class="form-slider"><label for="duration">Duration (weeks):</label><input type="number" id="duration" name="duration" value="<?php vecho('duration'); ?>" /></div>
      <div class="form-slider"><label for="salary">Compensation / Stipend ($US):</label><input type="number" id="salary" name="salary" value="<?php vecho('salary'); ?>" /></div>
      <right>
        <input type="radio" name="salarytype" value="month" <?php vchecked('salarytype', 'month'); ?> /> / month
        <input type="radio" name="salarytype" value="day" <?php vchecked('salarytype', 'day'); ?> /> / day
        <input type="radio" name="salarytype" value="hour" <?php vchecked('salarytype', 'hour'); ?> /> / hour
        <input type="radio" name="salarytype" value="total" <?php vchecked('salarytype', 'total'); ?> /> total payment
      </right>
      <div class="form-slider"><label for="deadline">Deadline for Application (mm/dd/yyyy):</label><input type="text" id="deadline" name="deadline" value="<?php vecho('deadline'); ?>" /></div>
      <div class="form-slider"><label for="desc">Job Description:</label><textarea id="desc" name="desc"><?php vecho('desc'); ?></textarea></div>
      <div class="form-slider"><label for="Requirements">Requirements:</label><input type="text" id="Requirements" name="requirements" value="<?php vecho('requirements'); ?>" /></div>
      <div class="form-slider"><label for="link">Listing URL:</label><input type="text" id="link" name="link" value="<?php vecho('link'); ?>" /></div>
      <div class="form-slider"><label for="location">Job Location:</label><input type="text" id="location" name="location" value="<?php vecho('location'); ?>" /></div>
      <?php vnotice(); ?>
      <right><input type="submit" name="<?php vecho('submitname'); ?>" value="<?php vecho('submitvalue'); ?>" /></right>
    </form>
  </div>
</panel>