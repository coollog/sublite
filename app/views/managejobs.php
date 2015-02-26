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
    <div>(Analytics Under Construction)</div>
    <?php
      function jobBlock($job) {
        $title = $job['title'];
        $location = $job['location'];
        $desc = strmax($job['desc'], 300);
        $deadline = $job['deadline'];
        if($job['locationtype'] == 'home') {
          return "
          <div class=\"jobblock\">
            <div class=\"title\">$title | Work at home</div>
            <div class=\"desc\">$desc</div>
            <div class=\"info\">Deadline: $deadline</div>
          </div>
        ";
        }
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
        echo "<b style=\"font-size: 1.5em;\">Congratulations! You have completed your company profile and are on your way to recruiting the most talented students. Just take a moment to complete your job listing(s) by clicking the button below and you'll be all set!</b><br /><br />" . vlinkto('<input type="button" value="List Job" />', 'addjob');
      }
    ?>
  </div>
</panel>