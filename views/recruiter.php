<style>
  .recruiterpic {
    width: 200px;
    height: 200px;
    background: transparent no-repeat center center;
    background-size: cover;
    border-radius: 100px;
    display: inline-block;
  }
  .recruiter subheadline {
    color: #000;
  }
  .recruiterinfo {
    margin: 40px 0;
  }
</style>

<panel class="recruiter">
  <div class="content">
    <headline>Recruiter Profile</headline>
    <?php if (vget('isme')) { ?>
      <a href="editprofile.php"><input type="button" value="Edit Profile" /></a>
    <?php } ?>

    <div class="recruiterinfo">
      <div class="recruiterpic" style="background-image: url('<?php vecho('photo'); ?>');"></div>

      <subheadline><?php vecho('firstname'); ?> <?php vecho('lastname'); ?></subheadline>
      <?php vecho('title'); ?> at <?php vecho('company'); ?>

      <?php if (count($jobs = vget('jobtitles')) > 0) { ?>
        <subheadline>Looking for</subheadline>
      <?php foreach (array_unique($jobs) as $job) echo "$job<br />"; } ?>

      <?php if (count($jobs = vget('joblocations')) > 0) { ?>
        <subheadline>Recruitment Locations</subheadline>
      <?php foreach (array_unique($jobs) as $job) echo "$job<br />"; } ?>
    </div>

    <?php if (vget('isme')) { ?>
      <a href="editprofile.php"><input type="button" value="Edit Profile" /></a>
    <?php } ?>
  </div>
</panel>