<style>
  .recruiterpic {
    width: 200px;
    height: 200px;
    background: transparent no-repeat center center;
    background-size: cover;
    border-radius: 100px;
    display: inline-block;
  }
  .recruiter headline {
    margin-bottom: -20px;
  }
  .recruiter subheadline {
    color: #000;
  }
  .recruiterinfo {
    margin: 40px 0;
  }
  .topeditprofile {
    margin-top: 20px;
  }
</style>

<panel class="recruiter">
  <div class="content">
    <headline><?php vecho('firstname'); ?> <?php vecho('lastname'); ?></headline>
    <subheadline class="title"><?php vecho('title'); ?> at <?php vecho('company'); ?></subheadline>
    <?php if (vget('isme')) { ?>
      <a href="editprofile.php"><input type="button" value="Edit Profile" class="topeditprofile" /></a>
    <?php } ?>

    <div class="recruiterinfo">
      <div class="recruiterpic" style="background-image: url('<?php vecho('photo'); ?>');"></div>

      <?php if (count($jobs = vget('jobtitles')) > 0) { ?>
        <subheadline>Looking for</subheadline>
      <?php foreach (array_unique($jobs) as $job) echo "$job<br />"; } ?>

      <?php if (count($jobs = vget('joblocations')) > 0) { ?>
        <subheadline>Recruitment Locations</subheadline>
      <?php foreach (array_unique($jobs) as $job) echo "$job<br />"; } ?>

      <?php if (vget('Loggedinstudent')) { ?>
        <br /><br />
        <?php
          View::partial('newmessage', [
            'from' => View::get('L_id'),
            'to' => View::get('recruiterid'),
            'text' => 'Message'
          ]);
        ?>
      <?php } ?>
    </div>

    <a href="search.php?byrecruiter=<?php vecho('recruiterid'); ?>"><input type="button" value="View Job Listings" /></a>

    <?php if (vget('isme')) { ?>
      <br /><br />
      <a href="editprofile.php"><input type="button" value="Edit Profile" /></a>
    <?php } ?>
  </div>
</panel>