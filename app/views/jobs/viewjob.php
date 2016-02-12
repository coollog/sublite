<style>
  panel.main {
    background: url('<?php vecho("companybanner") ?>') no-repeat center center;
    background-size: cover;
    display: table;
    height: 200px;
  }
  panel.main .banner {
    padding: 30px 0;
    background: rgba(0, 0, 0, 0.5);
  }
  panel.main .banner .tagline {
    color: #ffd800;
    font-size: 4em;
    text-transform: uppercase;
    text-shadow: 2px 2px #035d75;
    line-height: 1em;
    margin-bottom: 0.2em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
  }
  panel.main .apply {
    font-size: 1.5em;
    color: #035d75;
    text-transform: uppercase;
    box-shadow: 2px 2px 0px #035d75;
  }
  panel.main .apply:hover {
    color: #fff;
  }
  .jobinfo {
    margin: 40px 0;
    text-align: left;
    width: 100%;
  }
  .jobtable {
    border-spacing: 10px;
    border-collapse: separate;
  }
  .jobtable td {
    vertical-align: top;
  }
  .jobtable td.col {
    width: 30%;
  }
  .icon {
    height: 100px;
    background: transparent no-repeat center left;
    background-size: 100px 100px;
    display: table;
    padding-left: 100px;
    width: 300px;
  }
  .icon subheadline {
    color: #000;
  }

  #jobtitle {
    margin-bottom: 10px;
    line-height: 1em;
  }

  #jobsubtitle {
    margin-bottom: 20px;
  }
</style>

<templates>
  <applytemplate>
    <?php
      $_id = View::get('_id');
      $href = "apply/$_id";

      if(vget('Loggedinstudent') || vget('Loggedin')) {
        $buttonText = "Apply Now";
        $onClick = "";

        // $hasApplication = View::get('hasApplication');
        // if ($hasApplication) {
        //   $href = "apply/$_id";
        // } else {
        //   $link = View::get('link');
        //   $isEmail = filter_var($link, FILTER_VALIDATE_EMAIL);
        //   if ($isEmail) {
        //     $buttonText = "Apply by Email";
        //   }
        //   $href = "../redirect?id=$_id&url=$link";
        //   $onClick = "onClick='return clickApply();'";
        // }
    ?>
        <a href="<?php echo $href; ?>" <?php echo $onClick; ?>>
          <input type="button" value="<?php echo $buttonText; ?>" />
        </a>
    <?php } else {
      echo vlinkto(
        '<input type="button" class="apply"
        value="Login or register to apply for this opening!" />',
        'login');
    }
    ?>
  </applytemplate>
</templates>

<script>
  function clickApply() {
    return confirm(
      'You have clicked on an external link and are leaving the pages of SubLite.net. We are not responsible for the accuracy or effectiveness of any content outside of SubLite.net.');
  }
  $(function() {
    Templates.init();

    var applyHTML = Templates.use('applytemplate', {});
    $('apply').html(applyHTML);

    $('#desc a').attr('href', '<?php echo $href; ?>');
  });
</script>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">Look inside <?php vecho('companyname'); ?></div>
        <?php echo vlinkto('<input type="button" class="button" value="View Company Profile" />', 'company', array('id' => vget('companyid')), true); ?></div>
      </div>
    </div>
  </div>
</panel>
<panel class="job">
  <div class="content">
    <headline id="jobtitle"><?php vecho('title'); ?></headline>
    <subheadline id="jobsubtitle">
      <?php
        $jobtype = vget('jobtype');
        switch ($jobtype) {
          case 'internship': echo 'Internship'; break;
          case 'fulltime': echo 'Full-time Position'; break;
          case 'parttime': echo 'Part-time Position'; break;
        }
      ?>
    </subheadline>

    <apply></apply>

    <br /><br />
    <?php vpartial('fb', array('route' => 'jobs/job.php?id='.vget('_id'))); ?>

    <div class="jobinfo">
      <table class="jobtable">
        <tr>
          <td style="width: 60%;">
            <div style="overflow: hidden; width: 100%; white-space: pre-line;">
              <div id="desc">
                <?php echo autolink(View::get('desc')); ?>
              </div>
              <subheadline>Requirements</subheadline>
              <?php vecho('requirements'); ?>
              <subheadline>Posted By</subheadline>
              <?php
                echo vlinkto(vget('recruitername'), 'recruiter', array('id' => vget('recruiterid')));
                if(vget('Loggedinstudent')) {
              ?>
                  | <a href="newmessage.php?from=<?php vecho('L_id'); ?>&to=<?php vecho('recruiterid'); ?>" onClick="return confirm('I have read, fully understand, and agree to Sublite’s Terms of Service and Privacy Policy. I agree to contact the recruiter in good-faith to inquire about the listing.')">Contact</a>
              <?php
                }
                else if(!vget('Loggedin')) {
              ?>
                  | <?php echo vlinkto('Create an account to message this recruiter!', 'register'); ?>
              <?php
                }
              ?>
            </div>
          </td>
          <td style="width: 30%;" style="vertical-align: middle;">

            <div class="icon" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/deadlineico.png');">
              <div class="cell">
                <subheadline><?php vecho('deadline'); ?></subheadline>
                <small>Deadline of application</small>
              </div>
            </div>
            <div class="icon" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/locationico.png');">
              <div class="cell">
                <subheadline>
                  <?php
                    if(vget('locationtype') == "home") echo 'Work at home!';
                    else {
                      vecho('location');
                    }
                  ?>
                </subheadline>
                <small>Location</small>
              </div>
            </div>
            <div class="icon"
              <?php
                if((!vget('duration') && !vget('startdate'))) {
                  echo 'style="display: none"';
                }
                else {
                  echo 'style="background-image: url(\''.$GLOBALS['dirpre'].'assets/gfx/durationico.png\');"';
                }
              ?>
            >
              <div class="cell">
                <subheadline>
                <?php
                  $duration = vget('duration');
                  $startdate = vget('startdate');
                  $enddate = vget('enddate');
                  if($duration) {
                    echo $duration . ' weeks';
                  }
                  if($startdate) {
                    if($duration) echo ', ';
                    if($startdate && $enddate) {
                      if(!$duration) {
                        echo 'F';
                      }
                      else {
                        echo 'f';
                      }
                      echo 'rom ' . $startdate . ' to ' . $enddate;
                    }
                    else {
                      if(!$duration) {
                        echo 'S';
                      }
                      else {
                        echo 's';
                      }
                      echo 'tarts on ' . $startdate;
                    }
                  }
                ?>
                </subheadline>
                <small>Duration</small>
              </div>
            </div>
            <div class="icon" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/salaryico.png');">
              <div class="cell">
                <subheadline>
                <?php
                  if(vget('salarytype') != "other") echo '$';
                  vecho('salary');
                  if(vget('salarytype') != "other") {
                    echo ' / ';
                    vecho('salarytype');
                  }
                ?>
                </subheadline>
                <small>Compensation</small>
              </div>
            </div>

          </td>
        </tr>
      </table>
    </div>

    <?php vpartial('fb', array('route' => 'jobs/job.php?id='.vget('_id'))); ?>
    <br /><br />

    <apply></apply>
  </div>
</panel>