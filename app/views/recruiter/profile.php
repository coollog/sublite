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
    <headline><?php View::echof('firstname'); ?> <?php View::echof('lastname'); ?></headline>
    <subheadline class="title"><?php View::echof('title'); ?> at <?php View::echof('company'); ?></subheadline>
    <?php if (View::get('isme')) { ?>
      <a href="editprofile.php"><input type="button" value="Edit Profile" class="topeditprofile" /></a>
    <?php } ?>

    <div class="recruiterinfo">
      <div class="recruiterpic" style="background-image: url('<?php View::echof('photo'); ?>');"></div>

      <?php if (count($jobs = View::get('jobtitles')) > 0) { ?>
        <subheadline>Looking for</subheadline>
      <?php foreach (array_unique($jobs) as $job) echo "$job<br />"; } ?>

      <?php if (count($jobs = View::get('joblocations')) > 0) { ?>
        <subheadline>Recruitment Locations</subheadline>
      <?php foreach (array_unique($jobs) as $job) echo "$job<br />"; } ?>

      <?php if (View::get('Loggedinstudent')) { ?>
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

    <a href="search.php?byrecruiter=<?php View::echof('recruiterid'); ?>"><input type="button" value="View Job Listings" /></a>

    <?php if (View::get('isme')) { ?>
      <br /><br />
      <a href="editprofile.php"><input type="button" value="Edit Profile" /></a>
    <?php } ?>
  </div>
</panel>