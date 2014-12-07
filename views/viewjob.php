<style>
  .jobinfo {
    margin: 40px 0;
    text-align: left;
  }
</style>

<panel class="job">
  <div class="content">
    <headline><?php vEcho('title'); ?></headline>
    <a href="<?php vEcho('link'); ?>" target="_blank"><input type="button" value="Apply Now" /></a>

    <div class="jobinfo">
      <?php vEcho('desc'); ?>

      <subheadline>Duration</subheadline>
      <?php vEcho('duration'); ?>

      <subheadline>Compenation / Stipend</subheadline>
      <?php vEcho('salary'); ?> / <?php vEcho('salarytype'); ?>

      <subheadline>Requirements</subheadline>
      <?php vEcho('requirements'); ?>

      <subheadline>Deadline for Application</subheadline>
      <?php vEcho('deadline'); ?>

      <subheadline>Job Location</subheadline>
      <?php vEcho('location'); ?>
    </div>

    <a href="<?php vEcho('link'); ?>" target="_blank"><input type="button" value="Apply Now" /></a>
  </div>
</panel>