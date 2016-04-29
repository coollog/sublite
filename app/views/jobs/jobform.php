<script>
  $(function () {
    var salaryText = $('label[for=salary]').html();
    var commissionText = 'Commission:';

    function setSalaryLabel() {
      if ($('#commission').is(':checked')) {
        $('label[for=salary]').html(commissionText).click();
      } else {
        $('label[for=salary]').html(salaryText).click();
      }
    }
    $('#commission').change(setSalaryLabel);
    setSalaryLabel();
  });
</script>

<panel class="form">
  <div class="content">
    <headline><?php View::echof('headline'); ?> Job Listing</headline>
    <form method="post">
      <?php
        if (View::get('_id') !== null) {
          $id = View::get('_id');
          echo ' &nbsp; ' . View::linkTo('<input type="button" value="View Job Listing" /><br /><br />', 'job', array('id' => $id), true);
        }
      ?>
      <?php View::notice(); ?>
      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php View::echof('title'); ?>" required /></div>
      <left>
        <input type="radio" name="jobtype" id="fulltime" value="fulltime" <?php View::checked('jobtype', 'fulltime'); ?> required /><label for="fulltime"> Full-time position</label>
        <input type="radio" name="jobtype" id="parttime" value="parttime" <?php View::checked('jobtype', 'parttime'); ?> required /><label for="parttime"> Part-time position</label>
        <input type="radio" name="jobtype" id="internship" value="internship" <?php View::checked('jobtype', 'internship'); ?> required /><label for="internship"> Internship</label>
      </left>
      <div class="form-slider" id="durationdiv"><label for="duration">Duration (weeks):</label><input type="text" id="duration" name="duration" maxlength="100" value="<?php View::echof('duration'); ?>" /></div>
      <div class="form-slider"><label for="startdate">Start date (optional, mm/dd/yyyy):</label><input class="datepicker" type="text" id="startdate" name="startdate" maxlength="100" value="<?php View::echof('startdate'); ?>" /></div>
      <div class="form-slider" id="enddatediv"><label for="enddate">End date (optional, mm/dd/yyyy):</label><input class="datepicker" type="text" id="enddate" name="enddate" maxlength="100" value="<?php View::echof('enddate'); ?>" /></div>
      <div class="form-slider"><label for="salary">Compensation($):</label><input type="text" id="salary" name="salary" required maxlength="100" value="<?php View::echof('salary'); ?>" required /></div>
      <right>
        <input type="radio" name="salarytype" id="month" value="month"
          <?php View::checked('salarytype', 'month'); ?> required />
          <label for="month"> / month</label>
        <input type="radio" name="salarytype" id="week" value="week"
          <?php View::checked('salarytype', 'week'); ?> required />
          <label for="week"> / week</label>
        <input type="radio" name="salarytype" id="day" value="day"
          <?php View::checked('salarytype', 'day'); ?> required />
          <label for="day"> / day</label>
        <input type="radio" name="salarytype" id="hour" value="hour"
          <?php View::checked('salarytype', 'hour'); ?> required />
          <label for="hour"> / hour</label>
        <input type="radio" name="salarytype" id="total" value="total"
          <?php View::checked('salarytype', 'total'); ?> required />
          <label for="total"> total payment</label>
        <input type="radio" name="salarytype" id="commission" value="commission"
          <?php View::checked('salarytype', 'commission'); ?> required />
          <label for="commission"> commission</label>
        <input type="radio" name="salarytype" id="other" value="other"
          <?php View::checked('salarytype', 'other'); ?> required />
          <label for="other"> other (100 chars max)</label>
      </right>
      <div class="form-slider"><label for="deadline">Deadline for Application (mm/dd/yyyy):</label><input class="datepicker" type="text" id="deadline" name="deadline" value="<?php View::echof('deadline'); ?>" required /></div>
      <left>
        Please do not include links to external applications or emails. If your
        company has its own application site, please contact us at
        <a href="mailto:info@sublite.net">info@sublite.net</a> and our team will
        get in touch to work with you.
      </left>
      <div class="form-slider"><label for="desc">Job Description (2500 chars max):</label><textarea id="desc" name="desc" required maxlength="2500"><?php View::echof('desc'); ?></textarea></div>
      <div class="form-slider"><label for="requirements">Requirements (2000 chars max):</label><textarea id="requirements" name="requirements" required maxlength="2000"><?php View::echof('requirements'); ?></textarea></div>
      <left>
        <input type="checkbox" name="locationtype" id="locationtype" value="home" <?php View::checked('locationtype', 'home'); ?> /><label for="locationtype"> Work at home job</label>
      </left>
      <div class="form-slider" id="locationdiv"><label for="location">Job Location (Address, City, State):</label><input type="text" id="location" name="location" maxlength="500" value="<?php View::echof('location'); ?>" required /></div>
      <input type="checkbox" name="terms" id="terms" value="agree" required /> <label for="terms">I represent and warrant that I am employed by the company offering the job, that I have authority or permission to post this job, and that the description is accurate and not misleading.</label>
      <?php View::notice(); ?>
      <right>
        <input type="submit" name="<?php View::echof('submitname'); ?>" value="<?php View::echof('submitvalue'); ?>" />
      </right>
    </form>
  </div>
</panel>

<script>
  formunloadmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>