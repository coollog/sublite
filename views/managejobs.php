<panel>
  <div class="content">
    <headline>Manage Job Listings</headline>
    <?php
      $jobs = vget('jobs');
      foreach ($jobs as $job) {
        echo vlinkto($job['title'], 'editjob', array('id' => $job['_id']->{'$id'})) . '<br />';
      }
    ?>
  </div>
</panel>