<style>
  .studentpic {
    width: 200px;
    height: 200px;
    background: transparent no-repeat center center;
    background-size: cover;
    border-radius: 100px;
    display: inline-block;
  }
  .student headline {
    margin-bottom: -20px;
  }
  .student subheadline {
    color: #000;
  }
  .studentinfo {
    margin: 40px 0;
  }
  .topeditprofile {
    margin-top: 20px;
  }
</style>

<panel class="student">
  <div class="content">
    <headline><?php View::echof('name'); ?></headline>
    <subheadline class="title"><?php View::echof('school'); ?> '<?php View::echof('class'); ?></subheadline>
    <div class="studentinfo">
      <div class="studentpic" style="background-image: url('<?php View::echof('photo'); ?>');"></div>

      <?php if (View::get('Loggedinstudent')) { ?>
        <br /><br />
        <?php
          View::partial('newmessage', [
            'from' => View::get('L_id'),
            'to' => View::get('studentid'),
            'text' => 'Message'
          ]);
        ?>
      <?php } ?>
    </div>
  </div>
</panel>