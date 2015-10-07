<style>
  .studentpic {
    background: transparent no-repeat center center;
    background-size: cover;
    width: 100px;
    height: 100px;
    border-radius: 50px;
    margin: 0 auto;
  }
</style>
<panel>
  <div class="content">
    <headline>Personal Profile</headline>
    <div class="studentinfo">
      <div class="studentpic" style="background-image: url('<?php vecho('photo'); ?>');"></div>

      <subheadline><?php vecho('name'); ?></subheadline>
      <?php vecho('school'); ?> '<?php vecho('class'); ?>

      <br /><br />
      <div><?php echo vlinkto('<input type="button" value="Edit Profile" />', 'editprofile'); ?></div>
    </div>
  </div>
</panel>