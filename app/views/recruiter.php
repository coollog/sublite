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

      <?php if (!vget('isme')) { ?>
        <br /><br />
        <a href="newmessage.php?from=<?php vecho('L_id'); ?>&to=<?php vecho('recruiterid'); ?>" onClick="return confirm('I have read, fully understand, and agree to Sublite’s Terms of Service and Privacy Policy. I agree to contact the recruiter in good-faith to inquire about the listing.')"><input type="button" value="Message" /></a>
      <?php } ?>
    </div>

    <a href="search.php?recruiter=<?php vecho('recruiterid'); ?>"><input type="button" value="View Job Listings" /></a>

    <?php if (vget('isme')) { ?>
      <br /><br />
      <a href="editprofile.php"><input type="button" value="Edit Profile" /></a>
    <?php } ?>
  </div>
</panel>