<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Job Listing</headline>
    <form method="post">
      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" required /></div>
      <div class="form-slider"><label for="duration">Duration (weeks):</label><input type="number" id="duration" name="duration" value="<?php vecho('duration'); ?>" required /></div>
      <div class="form-slider"><label for="salary">Compensation / Stipend ($US):</label><input type="number" id="salary" name="salary" value="<?php vecho('salary'); ?>" required /></div>
      <right>
        <input type="radio" name="salarytype" id="month" value="month" <?php vchecked('salarytype', 'month'); ?> required /><label for="month"> / month</label>
        <input type="radio" name="salarytype" id="day" value="day" <?php vchecked('salarytype', 'day'); ?> /><label for="day"> / day</label>
        <input type="radio" name="salarytype" id="hour" value="hour" <?php vchecked('salarytype', 'hour'); ?> /><label for="hour"> / hour</label>
        <input type="radio" name="salarytype" id="total" value="total" <?php vchecked('salarytype', 'total'); ?> /><label for="total"> total payment</label>
      </right>
      <div class="form-slider"><label for="deadline">Deadline for Application (mm/dd/yyyy):</label><input type="text" id="deadline" name="deadline" value="<?php vecho('deadline'); ?>" required /></div>
      <div class="form-slider"><label for="desc">Job Description (2000 chars max):</label><textarea id="desc" name="desc" required maxlength="2000"><?php vecho('desc'); ?></textarea></div>
      <div class="form-slider"><label for="Requirements">Requirements:</label><input type="text" id="Requirements" name="requirements" value="<?php vecho('requirements'); ?>" required /></div>
      <div class="form-slider"><label for="link">Listing URL:</label><input type="text" id="link" name="link" value="<?php vecho('link'); ?>" required /></div>
      <div class="form-slider"><label for="location">Job Location:</label><input type="text" id="location" name="location" value="<?php vecho('location'); ?>" required /></div>
      <input type="checkbox" name="terms" id="terms" value="agree" required /> <label for="terms">I represent and warrant that I am employed by the company offering the internship, that I have authority or permission to post this internship, and that the description is accurate and not misleading.</label>
      <?php vnotice(); ?>
      <right>
        <input type="submit" name="<?php vecho('submitname'); ?>" value="<?php vecho('submitvalue'); ?>" />
        <?php 
          if (vget('_id') !== null) {
            $id = vget('_id');
            echo ' &nbsp; ' . vlinkto('<input type="button" value="View Job Listing" />', 'job', array('id' => $id));
          }
        ?>
      </right>
    </form>
  </div>
</panel>