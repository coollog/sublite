<panel class="form">
  <div class="content">
    <headline><?php vecho('headline'); ?> Company Profile</headline>
    <form method="post">
      <div class="form-slider"><label for="name">Company Name:</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" disabled /></div>
      <div class="form-slider"><label for="industry">Industry:</label><input type="text" id="industry" name="industry" value="<?php vecho('industry'); ?>" /></div>
      <div class="form-slider"><label for="desc">Company Description:</label><textarea id="desc" name="desc"><?php vecho('desc'); ?></textarea></div>
      <?php vnotice(); ?>
      <right><input type="submit" name="<?php vecho('submitname'); ?>" value="<?php vecho('submitvalue'); ?>" /></right>
    </form>
  </div>
</panel>