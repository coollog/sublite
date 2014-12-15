<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Company Profile</headline>
    <form method="post">
      <div class="form-slider"><label for="name">Company Name*:</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" disabled /></div>
      <div class="form-slider"><label for="industry">What industry does your company belong to? You can choose up to 3 industries (click while holding down the Ctrl key to select more than 1 industry).*</label><input type="text" id="industry" name="industry" value="<?php vecho('industry'); ?>" required /></div>
      <div class="form-slider"><label for="size">What is the size of your company?*</label><select id="size" name = "size" required>
        <option selected="selected"><?php vecho('size'); ?></option>
        <option>Very small (1-49 employees)</option>
        <option>Small (50-299 employees)</option>
        <option>Medium (300-999 employees)</option>
        <option>Large (1000-9999 employees)</option>
        <option>Very large (10,000+ employees)</option>
      </select></div>

      <div class="form-slider"><label for="desc" class="fortextarea">What does your company do? (max. 1000 characters)*</label><textarea id="desc" name="desc" required maxlength="1000"><?php vecho('desc'); ?></textarea></div>

      <div class="form-slider"><label for="founded" class="fortextarea">When was your company founded? Please specify a year (e.g. 1978). (max. 15 characters)*</label><input type="text" id="founded" name="founded" value="<?php vecho('founded'); ?>" required /></div>

      <div class="form-slider"><label for="location" class="fortextarea">Where is/are your company office(s) located? You can choose more than one (click while holding down the Ctrl key to select more than 1 location).*</label><input type="text" id="location" name="location" value="<?php vecho('location'); ?>" required /></div>      

      <div class="form-slider"><label for="corevalues" class="fortextarea">What are your company's core values?* (max. 1000 characters)</label><textarea id="corevalues" name="corevalues" required maxlength="1000"><?php vecho('corevalues'); ?></textarea></div>


      
      <input type="hidden" name="bannerphoto" value="<?php vecho('bannerphoto'); ?>" />
      <subheadline>What would you like your banner image to be?*</subheadline>
      <div class="iframe"><iframe class="S3" src="S3.php"></iframe></div>
      <subheadline>Current Photo</subheadline>
      <div class="img"><img src="<?php vecho('bannerphoto'); ?>" /></div>

      <input type="hidden" name="logophoto" value="<?php vecho('logophoto'); ?>" />
      <subheadline>What is your company logo?*</subheadline>
      <div class="iframe"><iframe class="S3" src="S3.php"></iframe></div>
      <subheadline>Current Photo</subheadline>
      <div class="img"><img src="<?php vecho('logophoto'); ?>" /></div>

      <br>
      The following questions are not mandatory but are highly recommended. These questions help students get a better understanding of the unique culture at your company. Please answer at least 6 of the following 13 questions.
      <br>

      <div class="form-slider"><label for="funfacts" class="fortextarea">What are the top 3 fun or quirky facts about your company? (max. 500 characters)</label><textarea id="funfacts" name="funfacts" maxlength="500"><?php vecho('funfacts'); ?></textarea></div>

      <div class="form-slider"><label for="society" class="fortextarea">How does your company give back to society? (max. 500 characters)</label><textarea id="society" name="society" maxlength="500"><?php vecho('society'); ?></textarea></div>

      <div class="form-slider"><label for="socialevent" class="fortextarea">What is a popular company-wide social event? (max. 500 characters)</label><textarea id="socialevent" name="socialevent" maxlength="500"><?php vecho('socialevent'); ?></textarea></div>

      <div class="form-slider"><label for="colorscheme" class="fortextarea">What is the color scheme of your office? (max. 500 characters)</label><textarea id="colorscheme" name="colorscheme" maxlength="500"><?php vecho('colorscheme'); ?></textarea></div>

      <div class="form-slider"><label for="media" class="fortextarea">What does the media say about your company? Direct quotes with source are recommended. (max. 500 characters)</label><textarea id="media" name="media" maxlength="500"><?php vecho('media'); ?></textarea></div>

      <div class="form-slider"><label for="employees" class="fortextarea">What does your employees say about your company? Direct quotes with source are recommended. (max. 500 characters)</label><textarea id="employees" name="employees" maxlength="500"><?php vecho('employees'); ?></textarea></div>

      <div class="form-slider"><label for="perks" class="fortextarea">What are the top 3 perks of working at your company? (max. 1000 characters):</label><textarea id="perks" name="perks" maxlength="500"><?php vecho('perks'); ?></textarea></div>

      <div class="form-slider"><label for="forfun" class="fortextarea">What sports, hobbies, or games do your employees enjoy? (max. 500 characters)</label><textarea id="forfun" name="forfun" maxlength="500"><?php vecho('forfun'); ?></textarea></div>

      <div class="form-slider"><label for="dessert" class="fortextarea">What type of dessert is your company and why? (max. 500 characters)</label><textarea id="dessert" name="dessert" maxlength="500"><?php vecho('dessert'); ?></textarea></div>

      <div class="form-slider"><label for="talent" class="fortextarea">What is the most interesting hidden talent of one of your employees? (max. 500 characters)</label><textarea id="talent" name="talent" maxlength="500"><?php vecho('talent'); ?></textarea></div>

      <div class="form-slider"><label for="dresscode" class="fortextarea">What is the dress code at the office? (max. 500 characters)</label><textarea id="dresscode" name="dresscode" maxlength="500"><?php vecho('dresscode'); ?></textarea></div>

      <div class="form-slider">Please write your own question and answer below.<br><label for="freequestion1" class="fortextarea">Question:</label><textarea id="freequestion1" name="freequestion1" maxlength="100"><?php vecho('freequestion1'); ?></textarea><br><label for="freeanswer1" class="fortextarea">Answer:</label><textarea id="freeanswer1" name="freeanswer1" maxlength="500"><?php vecho('freeanswer1'); ?></textarea></div>

      <div class="form-slider">Please write your own question and answer below.<br><label for="freequestion2" class="fortextarea">Question:</label><textarea id="freequestion2" name="freequestion2" maxlength="100"><?php vecho('freequestion2'); ?></textarea><br><label for="freeanswer2" class="fortextarea">Answer:</label><textarea id="freeanswer2" name="freeanswer2" maxlength="500"><?php vecho('freeanswer2'); ?></textarea></div>

      <?php vnotice(); ?>
      <right><input type="submit" name="<?php vecho('submitname'); ?>" value="<?php vecho('submitvalue'); ?>" /></right>
    </form>
  </div>
</panel>