<style>
  .jobblock {
    text-align: left;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
    color: #000;
  }
  .jobblock:hover {
    opacity: 0.5;
  }
  .jobblock .title {
    font-size: 1.5em;
    color: #03596a;
    line-height: 40px;
  }
  .jobblock .info {
    opacity: 0.5;
    line-height: 40px;
  }
</style>

<panel class="jobs">
  <div class="content">
    <headline>Manage Job Listings</headline>
    <?php
      function jobBlock($job) {
        $title = $job['title'];
        $location = $job['location'];
        $desc = $job['desc'];
        $deadline = $job['deadline'];
        return "
          <div class=\"jobblock\">
            <div class=\"title\">$title | $location</div>
            <div class=\"desc\">$desc</div>
            <div class=\"info\">Deadline: $deadline</div>
          </div>
        ";
      }
      $jobs = vget('jobs');
      foreach ($jobs as $job) {
        echo vlinkto(jobBlock($job), 'editjob', array('id' => $job['_id']->{'$id'}));
      }
      if ($jobs->count() == 0) {
        echo "No jobs yet.<br /><br />" . vlinkto('<input type="button" value="List Job" />', 'addjob');
      }
    ?>
  </div>
</panel>