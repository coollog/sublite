<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Company Profile</headline>
    <form method="post">
      <div class="form-slider"><label for="name">Company Name:</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" disabled /></div>
      <div class="form-slider"><label for="industry">Industry:</label><input type="text" id="industry" name="industry" value="<?php vecho('industry'); ?>" required /></div>

      <div class="form-slider"><label for="desc" class="fortextarea">Please provide a brief description of what your company does and it's core values. (2000 chars max):</label><textarea id="desc" name="desc" required maxlength="2000"><?php vecho('desc'); ?></textarea></div>

      <div class="form-slider"><label for="funfacts" class="fortextarea">What are the top 10 fun facts about your company? (2000 chars max):</label><textarea id="funfacts" name="funfacts" required maxlength="2000"><?php vecho('funfacts'); ?></textarea></div>

      <div class="form-slider"><label for="forfun" class="fortextarea">What do your employees do for fun? (2000 chars max):</label><textarea id="forfun" name="forfun" required maxlength="2000"><?php vecho('forfun'); ?></textarea></div>

      <div class="form-slider"><label for="whyunique" class="fortextarea">What do you think makes your company unique? (2000 chars max):</label><textarea id="whyunique" name="whyunique" required maxlength="2000"><?php vecho('whyunique'); ?></textarea></div>

      <div class="form-slider"><label for="adjectives" class="fortextarea">What are 5 adjectives you would use to describe working at the company? (200 chars max):</label><textarea id="adjectives" name="adjectives" required maxlength="200"><?php vecho('adjectives'); ?></textarea></div>

      <div class="form-slider"><label for="perks" class="fortextarea">What are the top 3 perks of working at your firm? (1000 chars max):</label><textarea id="perks" name="perks" required maxlength="1000"><?php vecho('perks'); ?></textarea></div>

      <?php vnotice(); ?>
      <right><input type="submit" name="<?php vecho('submitname'); ?>" value="<?php vecho('submitvalue'); ?>" /></right>
    </form>
  </div>
</panel>