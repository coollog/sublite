<style>
  panel.main {
    background: url('<?php View::echof("companybanner") ?>') no-repeat center center;
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

      if(View::get('Loggedinstudent') || View::get('Loggedin')) {
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
      echo View::linkTo(
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

    $('#desc a, #requirements a').attr('href', '<?php echo $href; ?>');
  });
</script>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">Look inside <?php View::echof('companyname'); ?></div>
        <?php echo View::linkTo('<input type="button" class="button" value="View Company Profile" />', 'company', array('id' => View::get('companyid')), true); ?></div>
      </div>
    </div>
  </div>
</panel>
<panel class="job">
  <div class="content">
    <headline id="jobtitle"><?php View::echof('title'); ?></headline>
    <subheadline id="jobsubtitle">
      <?php
        $jobtype = View::get('jobtype');
        switch ($jobtype) {
          case 'internship': echo 'Internship'; break;
          case 'fulltime': echo 'Full-time Position'; break;
          case 'parttime': echo 'Part-time Position'; break;
        }
      ?>
    </subheadline>

    <apply></apply>

    <br /><br />
    <?php View::partial('fb', array('route' => 'jobs/job.php?id='.View::get('_id'))); ?>

    <div class="jobinfo">
      <table class="jobtable">
        <tr>
          <td style="width: 60%;">
            <div style="overflow: hidden; width: 100%; white-space: pre-line;">
              <div id="desc">
                <?php echo autolink(View::get('desc')); ?>
              </div>
              <subheadline>Requirements</subheadline>
              <div id="requirements">
                <?php echo autolink(View::get('requirements')); ?>
              </div>
              <subheadline>Posted By</subheadline>
              <?php
                echo View::linkTo(View::get('recruitername'), 'recruiter', array('id' => View::get('recruiterid')));
                if(View::get('Loggedinstudent')) {
              ?>
                  | <a href="newmessage.php?from=<?php View::echof('L_id'); ?>&to=<?php View::echof('recruiterid'); ?>" onClick="return confirm('I have read, fully understand, and agree to Sublite’s Terms of Service and Privacy Policy. I agree to contact the recruiter in good-faith to inquire about the listing.')">Contact</a>
              <?php
                }
                else if(!View::get('Loggedin')) {
              ?>
                  | <?php echo View::linkTo('Create an account to message this recruiter!', 'register'); ?>
              <?php
                }
              ?>
            </div>
          </td>
          <td style="width: 30%;" style="vertical-align: middle;">

            <div class="icon" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/deadlineico.png');">
              <div class="cell">
                <subheadline><?php View::echof('deadline'); ?></subheadline>
                <small>Deadline of application</small>
              </div>
            </div>
            <div class="icon" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/locationico.png');">
              <div class="cell">
                <subheadline>
                  <?php
                    if(View::get('locationtype') == "home") echo 'Work at home!';
                    else {
                      View::echof('location');
                    }
                  ?>
                </subheadline>
                <small>Location</small>
              </div>
            </div>
            <div class="icon"
              <?php
                if((!View::get('duration') && !View::get('startdate'))) {
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
                  $duration = View::get('duration');
                  $startdate = View::get('startdate');
                  $enddate = View::get('enddate');
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
                    $salaryType = View::get('salarytype');
                    $showType =
                      ($salaryType != 'other' && $salaryType != 'commission');

                    if ($showType) echo '$';
                    View::echof('salary');
                    if ($showType) echo " / $salaryType";
                  ?>
                </subheadline>
                <small>
                  <?php if ($salaryType == 'commission') { ?>
                    Commission
                  <?php } else { ?>
                    Compensation
                  <?php } ?>
                </small>
              </div>
            </div>

          </td>
        </tr>
      </table>
    </div>

    <?php View::partial('fb', array('route' => 'jobs/job.php?id='.View::get('_id'))); ?>
    <br /><br />

    <apply></apply>
  </div>
</panel>