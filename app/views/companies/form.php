<panel class="form">
  <div class="content">
    <headline><?php View::echof('headline'); ?> Company Profile</headline>
    <?php if (View::get('submitname') == 'add') { ?>
      <i>This form auto-saves, so you may return to finish the form later.</i>
    <?php } ?>
    <form method="post" id="company">
      <?php
        if (View::get('_id') !== null) {
          $id = View::get('_id');
          echo ' &nbsp; ' . View::linkTo('<input type="button" value="View Company Profile" /><br /><br />', 'company', array('id' => $id));
        }
      ?>
      <?php View::notice(); ?>
      <div class="form-slider"><label for="name">Company Name*:</label><input type="text" id="name" name="name" value="<?php View::echof('name'); ?>" disabled /></div>

      <div class="form-slider"><label>Industry*:</label></div>
      <?php View::partial('industryselect'); ?>

      <div class="form-slider"><label for="size">Company size*: </label>
      <select id="size" name="size" required>
        <?php View::echof('size', '<option selected="selected">{var}</option>'); ?>
        <option>1-49 employees</option>
        <option>50-299 employees</option>
        <option>300-999 employees</option>
        <option>1000-9999 employees</option>
        <option>10,000+ employees</option>
      </select></div>

      <div class="form-slider"><label for="desc" class="fortextarea">What does your company do? (max. 1000 characters)*</label><textarea id="desc" name="desc" required maxlength="1000"><?php View::echof('desc'); ?></textarea></div>

      <div class="form-slider"><label for="founded">Year of founding?*</label><input type="text" id="founded" name="founded" value="<?php View::echof('founded'); ?>" required /></div>

      <div class="form-slider"><label for="location" class="fortextarea">Location of office(s)?*</label><input type="text" id="location" name="location" value="<?php View::echof('location'); ?>" required /></div>

      <div class="form-slider"><label for="corevalues" class="fortextarea">What are your company's core values?* (max. 1000 characters)</label><textarea id="corevalues" name="corevalues" required maxlength="1000"><?php View::echof('corevalues'); ?></textarea></div>

      <?php
        View::partial('s3single', array(
          's3name' => 'logophoto',
          's3title' => 'What is your company logo?*',
          's3link' => View::get('logophoto')
        ));
      ?>
      <?php
        View::partial('s3single', array(
          's3name' => 'bannerphoto',
          's3title' => 'What would you like your banner image to be? (must be at least 1000px wide)*',
          's3link' => View::get('bannerphoto')
        ));
      ?>

      <?php
        View::partial('s3multiple', array(
          's3name' => 'photos',
          's3title' => 'Additional photos (upload at least 4 more)*:',
          's3links' => View::get('photos')
        ));
      ?>

      <br>
      The following questions are not mandatory but are highly recommended. These questions help students get a better understanding of the unique culture at your company. Please answer <b>at least 6</b> of the following 13 questions.
      <br>

      <div class="form-slider"><label for="funfacts" class="fortextarea">What are the top 3 fun or quirky facts about your company? (max. 500 characters)</label><textarea id="funfacts" name="funfacts" maxlength="500"><?php View::echof('funfacts'); ?></textarea></div>

      <div class="form-slider"><label for="society" class="fortextarea">How does your company give back to society? (max. 500 characters)</label><textarea id="society" name="society" maxlength="500"><?php View::echof('society'); ?></textarea></div>

      <div class="form-slider"><label for="socialevent" class="fortextarea">What is a popular company-wide social event? (max. 500 characters)</label><textarea id="socialevent" name="socialevent" maxlength="500"><?php View::echof('socialevent'); ?></textarea></div>

      <div class="form-slider"><label for="colorscheme" class="fortextarea">What is the color scheme of your office? (max. 500 characters)</label><textarea id="colorscheme" name="colorscheme" maxlength="500"><?php View::echof('colorscheme'); ?></textarea></div>

      <div class="form-slider"><label for="media" class="fortextarea">What does the media say about your company? Direct quotes with source are recommended. (max. 500 characters)</label><textarea id="media" name="media" maxlength="500"><?php View::echof('media'); ?></textarea></div>

      <div class="form-slider"><label for="employees" class="fortextarea">What do your employees say about your company? Direct quotes with source are recommended. (max. 500 characters)</label><textarea id="employees" name="employees" maxlength="500"><?php View::echof('employees'); ?></textarea></div>

      <div class="form-slider"><label for="perks" class="fortextarea">What are the top 3 perks of working at your company? (max. 1000 characters):</label><textarea id="perks" name="perks" maxlength="500"><?php View::echof('perks'); ?></textarea></div>

      <div class="form-slider"><label for="forfun" class="fortextarea">What sports, hobbies, or games do your employees enjoy? (max. 500 characters)</label><textarea id="forfun" name="forfun" maxlength="500"><?php View::echof('forfun'); ?></textarea></div>

      <div class="form-slider"><label for="dessert" class="fortextarea">What type of dessert is your company and why? (max. 500 characters)</label><textarea id="dessert" name="dessert" maxlength="500"><?php View::echof('dessert'); ?></textarea></div>

      <div class="form-slider"><label for="talent" class="fortextarea">What is the most interesting hidden talent of one of your employees? (max. 500 characters)</label><textarea id="talent" name="talent" maxlength="500"><?php View::echof('talent'); ?></textarea></div>

      <div class="form-slider"><label for="dresscode" class="fortextarea">What is the dress code at the office? (max. 500 characters)</label><textarea id="dresscode" name="dresscode" maxlength="500"><?php View::echof('dresscode'); ?></textarea></div>

      <br>
      Please write your own questions and answers below.
      <br>

      <div class="form-slider"><label for="freequestion1">Question #1 (max. 100 characters)</label><input type="text" id="freequestion1" name="freequestion1" maxlength="100" value="<?php View::echof('freequestion1'); ?>" /></div>
      <div class="form-slider"><label for="freeanswer1">Answer #1 (max. 500 characters)</label><textarea id="freeanswer1" name="freeanswer1" maxlength="500"><?php View::echof('freeanswer1'); ?></textarea></div>

      <div class="form-slider"><label for="freequestion2">Question #2 (max. 100 characters)</label><input type="text" id="freequestion2" name="freequestion2" maxlength="100" value="<?php View::echof('freequestion2'); ?>" /></div>
      <div class="form-slider"><label for="freeanswer2">Answer #2 (max. 500 characters)</label><textarea id="freeanswer2" name="freeanswer2" maxlength="500"><?php View::echof('freeanswer2'); ?></textarea></div>

      <?php View::notice(); ?>
      <right>
        <input type="submit" name="<?php View::echof('submitname'); ?>" value="<?php View::echof('submitvalue'); ?>" />
      </right>
    </form>
  </div>
</panel>

<script>
  <?php if (View::get('submitname') == 'add') { ?>
    formunloadfunction(function() { saveForm('#company') });
    // Save form whenever it is modified.
    $(function () {
      $('#company').bind("DOMSubtreeModified", function() {
        saveForm('#company');
      });
      $('textarea, input, select').change(function() {
        saveForm('#company');
      });
    });
  <?php } else { ?>
    formunloadmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
  <?php } ?>

  function saveForm(form) {
    var inputData = {};
    $(form).find('input:not(:checkbox)').each(function() {
      inputData[$(this).attr('name')] = $(this).val();
    });
    var textareaData = {};
    $(form).find('textarea').each(function() {
      textareaData[$(this).attr('name')] = $(this).val();
    });
    var selectData = {};
    $(form).find('select').each(function() {
      selectData[$(this).attr('name')] = $(this).val();
    });
    var checkboxData = [];
    $(form).find('input:checked').each(function() {
      checkboxData.push({
        name: $(this).attr('name'),
        val: $(this).val()
      });
    });
    var images = {};
    $('.img').each(function () {
      var name = $(this).attr('name');
      var html = $(this).html();
      images[name] = html;
    });
    var imageinputs = {};
    $('.inputs').each(function () {
      var name = $(this).attr('name');
      var html = $(this).html();
      imageinputs[name] = html;
    });

    var formData = {
      'input': inputData,
      'textarea': textareaData,
      'checkbox': checkboxData,
      'select': selectData,
      'images': images,
      'imageinputs': imageinputs
    };
    localStorage.setItem('form'+form, JSON.stringify(formData));

    console.log('saved form');
  }
  function loadForm(form) {
    if (!localStorage.getItem('form'+form)) return;

    var formData = JSON.parse(localStorage.getItem('form'+form));

    var inputData = formData['input'];
    for (var name in inputData) {
      $(form).find('input[name="'+name+'"]').val(inputData[name]);
    }
    var textareaData = formData['textarea'];
    for (var name in textareaData) {
      $(form).find('textarea[name='+name+']').val(textareaData[name]);
    }
    var selectData = formData['select'];
    for (var name in selectData) {
      var val = selectData[name];
      $(form).find('select[name="'+name+'"]').find("option")
        .filter(function() { return $(this).val() == val; })
        .prop('selected', true);
    }
    var checkboxData = formData['checkbox'];
    for (var i = 0; i < checkboxData.length; i ++) {
      var name = checkboxData[i].name, val = checkboxData[i].val;
      // Fix [] error in selector
      name = name.replace('[]', '\\[\\]');
      $(form).find('input[name="'+name+'"]')
        .filter(function() { return $(this).val() == val; })
        .prop('checked', true);
    }
    var imageData = formData['images'];
    for (var name in imageData) {
      $('.img[name='+name+']').html(imageData[name]);
    }
    var imageInputsData = formData['imageinputs'];
    for (var name in imageInputsData) {
      $('.inputs[name='+name+']').html(imageInputsData[name]);
    }
    console.log('loaded form');
  }

  <?php
    if (View::get('submitname') == 'add' && View::get('Error') == '') {
      echo "loadForm('#company');";
    }
  ?>
</script>