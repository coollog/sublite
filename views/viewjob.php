<style>
  .jobinfo {
    margin: 40px 0;
    text-align: left;
    width: 100%;
  }
</style>

<panel class="job">
  <div class="content">
    <headline><?php vecho('title'); ?></headline>
    <a href="<?php vecho('link'); ?>" target="_blank"><input type="button" value="Apply Now" /></a>

    <div class="jobinfo">
      <?php vecho('desc'); ?>

      <subheadline>Duration</subheadline>
      <?php vecho('duration'); ?>

      <subheadline>Compensation / Stipend</subheadline>
      <?php vecho('salary'); ?> / <?php vecho('salarytype'); ?>

      <subheadline>Requirements</subheadline>
      <?php vecho('requirements'); ?>

      <subheadline>Deadline for Application</subheadline>
      <?php vecho('deadline'); ?>

      <subheadline>Job Location</subheadline>
      <?php vecho('location'); ?>
    </div>

    <a href="<?php vecho('link'); ?>" target="_blank"><input type="button" value="Apply Now" /></a>
  </div>
</panel>