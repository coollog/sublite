<style>
  .jobblock {
    text-align: left;
    padding: 0.5em 0;
    border-bottom: 1px solid #ddd;
    color: #000;
    width: 100%;
  }
  .jobblock .title {
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
  .jobblock .title:hover {
    opacity: 0.5;
  }
  .jobblock jobtitle {
    font-size: 1.5em;
    color: #03596a;
    line-height: 40px;
  }
  .jobblock location {
    opacity: 0.5;
  }
  .jobblock .buttons {
    text-align: right;
    margin: 1em 0;
    display: none;
  }
  .jobblock .buttons input {
    padding: 0 1.5em;
  }
</style>

<templates>
  <jobtemplate>
    <div class="jobblock">
      <div class="title">
        <a href="../jobs/job?id={_id}" target="_blank">
          <jobtitle>{title}</jobtitle><br />
          <location>{location}</location>
        </a>
      </div>
      <div class="buttons">
        <a href="editjob?id={_id}">
          <input type="button" value="Edit Job" />
        </a>
        <a href="editapplication/{_id}">
          <input type="button" value="{editorcreate}" />
        </a>
        <a href="viewapplicants/{_id}">
          <input type="button" value="View Applicants" />
        </a>
      </div>
    </div>
  </jobtemplate>
  <nojobstemplate>
    <b style="font-size: 1.5em;">
      Congratulations! You have completed your company profile and are on
      your way to recruiting the most talented students. Just take a moment
      to complete your job listing(s) by clicking the button below and
      you'll be all set!
    </b>
  </nojobstemplate>
</templates>

<jobData class="hide">
  <?php
    $jobs = View::get('jobs');
    echo toJSON($jobs);
  ?>
</jobData>

<script>
  function setupJobHover() {
    $('.jobblock').hover(function () {
      $(this).children('.buttons').finish().slideDown(200, 'easeInOutCubic');
    }, function () {
      $(this).children('.buttons').finish().slideUp(200, 'easeInOutCubic');
    })
  }

  $(function () {
    (function setupJobs() {
      var jobData = JSON.parse($('jobData').html());
      jobData.forEach(function (job) {
        var _id = job._id.$id;
        var title = job.title;
        var location = job.location;
        var editorcreate = job.application.questions.length == 0
          ? 'Create Application' : 'Edit Application';

        var data = {
          _id: _id,
          title: title,
          location: location,
          editorcreate : editorcreate
        }
        var jobHTML = useTemplate('jobtemplate', data);
        $('jobs').append(jobHTML);
      });
      if (jobData.length == 0) {
        var noJobsHTML = useTemplate('nojobstemplate', {});
        $('jobs').html(noJobsHTML);
      } else {
        setupJobHover();
      }
    })();
  });
</script>

<panel class="jobs">
  <div class="content">
    <headline>Manage Job Listings</headline>
    <?php
      $totalViewCount = 0;
      $totalApplyCount = 0;
      foreach ($jobs as $job) {
        $totalViewCount += $job['stats']['views'];
        $totalApplyCount += $job['stats']['clicks'];
      }
    ?>
    <div style="font-size: 16px;">
      You have a total of <b><?php echo $totalViewCount; ?></b> views on your
      listings and <b><?php echo $totalApplyCount; ?></b> clicks on the
      "Apply Now" button.
    </div>
    <br />

    <jobs></jobs>
    <br /><br />
    <a href="addjob">
      <input type="button" value="List Job" />
    </a>
    <fade class="div gaptop nohover">
      <i>
        You will receive 1 free <i>Credit</i>
        <a href="<?php echo $GLOBALS['dirpre']; ?>../faq#recruiters">(?)</a>
        for every job you list!
      </i>
    </fade>
  </div>
</panel>