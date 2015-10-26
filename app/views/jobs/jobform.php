<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Job Listing</headline>
    <form method="post">
      <?php
        if (vget('_id') !== null) {
          $id = vget('_id');
          echo ' &nbsp; ' . vlinkto('<input type="button" value="View Job Listing" /><br /><br />', 'job', array('id' => $id), true);
        }
      ?>
      <?php vnotice(); ?>
      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" required /></div>
      <left>
        <input type="radio" name="jobtype" id="fulltime" value="fulltime" <?php vchecked('jobtype', 'fulltime'); ?> required /><label for="fulltime"> Full-time position</label>
        <input type="radio" name="jobtype" id="parttime" value="parttime" <?php vchecked('jobtype', 'parttime'); ?> required /><label for="parttime"> Part-time position</label>
        <input type="radio" name="jobtype" id="internship" value="internship" <?php vchecked('jobtype', 'internship'); ?> required /><label for="internship"> Internship</label>
      </left>
      <div class="form-slider" id="durationdiv"><label for="duration">Duration (weeks):</label><input type="text" id="duration" name="duration" maxlength="100" value="<?php vecho('duration'); ?>" /></div>
      <div class="form-slider"><label for="startdate">Start date (optional, mm/dd/yyyy):</label><input class="datepicker" type="text" id="startdate" name="startdate" maxlength="100" value="<?php vecho('startdate'); ?>" /></div>
      <div class="form-slider" id="enddatediv"><label for="enddate">End date (optional, mm/dd/yyyy):</label><input class="datepicker" type="text" id="enddate" name="enddate" maxlength="100" value="<?php vecho('enddate'); ?>" /></div>
      <div class="form-slider"><label for="salary">Compensation / Stipend ($US):</label><input type="text" id="salary" name="salary" required maxlength="100" value="<?php vecho('salary'); ?>" required /></div>
      <right>
        <input type="radio" name="salarytype" id="month" value="month" <?php vchecked('salarytype', 'month'); ?> required /><label for="month"> / month</label>
        <input type="radio" name="salarytype" id="week" value="week" <?php vchecked('salarytype', 'week'); ?> /><label for="week"> / week</label>
        <input type="radio" name="salarytype" id="day" value="day" <?php vchecked('salarytype', 'day'); ?> /><label for="day"> / day</label>
        <input type="radio" name="salarytype" id="hour" value="hour" <?php vchecked('salarytype', 'hour'); ?> /><label for="hour"> / hour</label>
        <input type="radio" name="salarytype" id="total" value="total" <?php vchecked('salarytype', 'total'); ?> /><label for="total"> total payment</label>
        <input type="radio" name="salarytype" id="other" value="other" <?php vchecked('salarytype', 'other'); ?> /><label for="other"> other (100 chars max)</label>
      </right>
      <div class="form-slider"><label for="deadline">Deadline for Application (mm/dd/yyyy):</label><input class="datepicker" type="text" id="deadline" name="deadline" value="<?php vecho('deadline'); ?>" required /></div>
      <div class="form-slider"><label for="desc">Job Description (2500 chars max):</label><textarea id="desc" name="desc" required maxlength="2500"><?php vecho('desc'); ?></textarea></div>
      <div class="form-slider"><label for="requirements">Requirements (2000 chars max):</label><textarea id="requirements" name="requirements" required maxlength="2000"><?php vecho('requirements'); ?></textarea></div>
      Please input the link to your application form for this job. If you do not have an online application form, please input an email address for students to submit applications to:
      <div class="form-slider"><label for="link">Job Application URL or Contact Email:</label><input type="text" id="link" name="link" value="<?php vecho('link'); ?>" required /></div>
      <left>
        <input type="checkbox" name="locationtype" id="locationtype" value="home" <?php vchecked('locationtype', 'home'); ?> /><label for="locationtype"> Work at home job</label>
      </left>
      <div class="form-slider" id="locationdiv"><label for="location">Job Location (Address, City, State):</label><input type="text" id="location" name="location" maxlength="500" value="<?php vecho('location'); ?>" required /></div>
      <input type="checkbox" name="terms" id="terms" value="agree" required /> <label for="terms">I represent and warrant that I am employed by the company offering the job, that I have authority or permission to post this job, and that the description is accurate and not misleading.</label>
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