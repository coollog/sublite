<templates>
  <locationtemplate>
    <div class="form-slider locationdiv">
      <label for="location{index}">Job Location (Address, City, State):</label>
      <input type="text" id="location{index}" name="location[]" maxlength="500"
             value="{location}" />
    </div>
  </locationtemplate>
</templates>

<script>
  $(function () {
    Templates.init();

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

    if($("#fulltime, #parttime").is(":checked")){
      $("#durationdiv").hide();
      $("#enddatediv").hide();
    }

    $("#fulltime, #parttime").click(function(){
      $("#durationdiv").hide(400);
      $("#enddatediv").hide(400);
    });

    $("#internship").click(function(){
      $("#durationdiv").show(400);
      $("#enddatediv").show(400);
    });

    (function workFromHomeToggleLocations() {
      var tmpLocations = {};
      $('#locationtype').click(function() {
        if ($(this).is(":checked")) {
          $('input[name="location[]"]').each(function () {
            var id = $(this).attr('id');
            var val = $(this).val();
            tmpLocations[id] = val;
          });
          $('input[name="location[]"]').val('');
          $(".locationdivs").slideUp(200, 'easeOutCubic');
        } else {
          $('input[name="location[]"]').each(function () {
            var id = $(this).attr('id');
            $(this).val(tmpLocations[id]);
          });
          $(".locationdivs").slideDown(200, 'easeOutCubic');
        }
      });

      if ($("#locationtype").is(":checked")) {
        $('input[name="location[]"]').prop('required', false);
        $(".locationdivs").hide();
      } else {
        $('input[name="location[]"]').prop('required', true);
        $(".locationdivs").show();
      }
    })();

    (function JobLocations() {
      var jobLocations = this;

      this.add = function (location) {
        var html = Templates.use('locationtemplate', {
          index: new Date().getUTCMilliseconds(),
          location: location
        });
        $('.locationdivs').append(html);

        formSetup();

        $('input[name="location[]"]').off('keyup').on('keyup', function () {
          var allFilled = true;
          $('input[name="location[]"]').each(function () {
            if ($(this).val().length == 0) allFilled = false;
          });

          if (allFilled) jobLocations.add('');
        }).off('change').on('change', function () {
          if ($(this).val().length == 0) $(this).parent().remove();
        });
      }

      // Add all locations.
      <?php
        if (!View::get('locationtype')) {
          foreach (View::get('location') as $location) {
      ?>
            this.add('<?php echo $location; ?>');
      <?php
          }
        }
      ?>

      this.add('');
    })();
  });
</script>

<panel class="form">
  <div class="content">
    <headline><?php View::echof('headline'); ?> Job Listing</headline>
    <form method="post">
      <?php
        if (View::get('_id') !== null) {
          $id = View::get('_id');
          echo ' &nbsp; ' .
            View::linkTo(
              '<input type="button" value="View Job Listing" /><br /><br />',
              'job',
              [ 'id' => $id ], // Params
              true // New tab?
            );
        }
      ?>

      <?php View::notice(); ?>

      <div class="form-slider">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title"
               value="<?php View::echof('title'); ?>" required />
      </div>

      <left>
        <input type="radio" name="jobtype" id="fulltime" value="fulltime"
               required <?php View::checked('jobtype', 'fulltime'); ?> />
        <label for="fulltime"> Full-time position</label>

        <input type="radio" name="jobtype" id="parttime" value="parttime"
               required <?php View::checked('jobtype', 'parttime'); ?> />
        <label for="parttime"> Part-time position</label>

        <input type="radio" name="jobtype" id="internship" value="internship"
               required <?php View::checked('jobtype', 'internship'); ?> />
        <label for="internship"> Internship</label>
      </left>

      <div class="form-slider" id="durationdiv">
        <label for="duration">Duration (weeks):</label>
        <input type="text" id="duration" name="duration" maxlength="100"
               value="<?php View::echof('duration'); ?>" />
      </div>

      <div class="form-slider">
        <label for="startdate">Start date (optional, mm/dd/yyyy):</label>
        <input class="datepicker" type="text" id="startdate" name="startdate"
               maxlength="100" value="<?php View::echof('startdate'); ?>" />
      </div>

      <div class="form-slider" id="enddatediv">
        <label for="enddate">End date (optional, mm/dd/yyyy):</label>
        <input class="datepicker" type="text" id="enddate" name="enddate"
               maxlength="100" value="<?php View::echof('enddate'); ?>" />
      </div>

      <div class="form-slider">
        <label for="salary">Compensation($):</label>
        <input type="text" id="salary" name="salary" maxlength="100"
               value="<?php View::echof('salary'); ?>" required />
      </div>

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

      <div class="form-slider">
        <label for="deadline">Deadline for Application (mm/dd/yyyy):</label>
        <input class="datepicker" type="text" id="deadline" name="deadline"
               value="<?php View::echof('deadline'); ?>" required />
      </div>

      <left>
        Please do not include links to external applications or emails. If your
        company has its own application site, please contact us at
        <a href="mailto:info@sublite.net">info@sublite.net</a> and our team will
        get in touch to work with you.
      </left>

      <div class="form-slider">
        <label for="desc">Job Description (2500 chars max):</label>
        <textarea id="desc" name="desc" required
                  maxlength="2500"><?php View::echof('desc'); ?></textarea>
      </div>

      <div class="form-slider">
        <label for="requirements">Requirements (2000 chars max):</label>
        <textarea id="requirements" name="requirements" maxlength="2000"
                  required><?php View::echof('requirements'); ?></textarea>
      </div>

      <left>
        <input type="checkbox" name="locationtype" id="locationtype"
               value="home" <?php View::checked('locationtype', 'home'); ?> />
        <label for="locationtype"> Work at home job</label>
      </left>

      <div class="locationdivs">
        Job Locations:
      </div>

      <br />
      <input type="checkbox" name="terms" id="terms" value="agree" required />
      <label for="terms">
        I represent and warrant that I am employed by the company offering the
        job, that I have authority or permission to post this job, and that the
        description is accurate and not misleading.
      </label>

      <?php View::notice(); ?>

      <right>
        <input type="submit" name="<?php View::echof('submitname'); ?>"
               value="<?php View::echof('submitvalue'); ?>" />
      </right>
    </form>
  </div>
</panel>

<script>
  formunloadmsg(
    "Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>