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
      <div class="studentpic" style="background-image: url('<?php View::echof('photo'); ?>');"></div>

      <subheadline><?php View::echof('name'); ?></subheadline>
      <?php View::echof('school'); ?> '<?php View::echof('class'); ?>

      <br /><br />
      <div><?php echo View::linkto('<input type="button" value="Edit Profile" />', 'editprofile'); ?></div>
    </div>
  </div>
</panel>