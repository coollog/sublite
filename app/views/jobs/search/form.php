<panel class="search">
  <div class="content">
    <form method="get">
      <input type="hidden" name="recruiterId"
             value="<?php View::echof('recruiterId'); ?>" />
      <input type="hidden" name="companyId"
             value="<?php View::echof('companyId'); ?>" />

      <div class="form-slider">
        <label for="title">Job Title/Description:</label>
        <input type="text" id="title" name="title"
               value="<?php View::echof('title'); ?>" />
      </div>

      <div class="<?php if (!View::get('showSearch')) echo 'hide'; ?>">
        <div class="form-slider">
          <label for="industrylabel" id="ilabel">Industry:</label>
          <input type="hidden" id="industrylabel">
          <select id="industry" name="industry">
            <?php
              View::echof(
                'industry', '<option selected="selected">{var}</option>');
              // Process list of industries to make select form
              foreach (View::get('industries') as $industry) {
                echo "<option>$industry</option>";
              }
            ?>
          </select>
        </div>
        <script>
          function changeIndustryLabel(bool) {
            if (bool) {
              $('#ilabel').css('left', '-4.5em');
            } else {
              $('#ilabel').css('left', '0.5em;');
            }
          }
          $('#industry')
            .change(function() { changeIndustryLabel($(this).val().length > 0); })
            .ready(function() { changeIndustryLabel(<?php
              if (strlen(View::get('industry')) > 0) echo 'true';
              else echo 'false';
            ?>) });
        </script>

        <div class="form-slider"><label for="city">City:</label><input type="text" id="city" name="city" value="<?php View::echof('city'); ?>" /></div>
      </div>

      <a href="companies">See All Companies</a><br />

      <br /><input type="button" name="search" value="Search" />
    </form>
  </div>
</panel>