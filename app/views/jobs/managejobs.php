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
  }
</style>

<templates>
  <jobtemplate>
    <table class="jobblock"><tr>
      <td class="title">
        <a href="../jobs/job?id={_id}" target="_blank">
          <jobtitle>{title}</jobtitle><br />
          <location>{location}</location>
        </a>
      </td>
      <td class="buttons">
        <a href="editjob.php?id={_id}">
          <input type="button" value="Edit Job" />
        </a>
        <a href="editapplication/{_id}">
          <input type="button" value="Edit Application" />
        </a>
      </td>
    </tr></table>
  </jobtemplate>
  <nojobstemplate>
    <b style="font-size: 1.5em;">
      Congratulations! You have completed your company profile and are on
      your way to recruiting the most talented students. Just take a moment
      to complete your job listing(s) by clicking the button below and
      you'll be all set!
    </b>
    <br /><br />
    <a href="addjob">
      <input type="button" value="List Job" />
    </a>
  </nojobstemplate>
</templates>

<jobData class="hide">
  <?php
    $jobs = View::get('jobs');
    echo json_encode($jobs);
  ?>
</jobData>

<script>
  $(function () {
    (function setupJobs() {
      var jobData = JSON.parse($('jobData').html());
      jobData.forEach(function (job) {
        var _id = job._id.$id;
        var title = job.title;
        var location = job.location;

        var data = {
          _id: _id,
          title: title,
          location: location
        }
        var jobHTML = useTemplate('jobtemplate', data);
        $('jobs').append(jobHTML);
      });
      if (jobData.length == 0) {
        var noJobsHTML = useTemplate('nojobstemplate', {});
        $('jobs').html(noJobsHTML);
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
  </div>
</panel>