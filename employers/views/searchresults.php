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

<?php if (!is_null($company = vget('showCompany'))) { ?>
  <style>
    panel.main {
      background: url('<?php echo $company['bannerphoto']; ?>') no-repeat center center;
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
    panel.main .button {
      font-size: 1.5em;
      color: #035d75;
      text-transform: uppercase;
      box-shadow: 2px 2px 0px #035d75;
    }
    panel.main .button:hover {
      color: #fff;
    }
  </style>

  <panel class="main">
    <div class="cell">
      <div class="banner">
        <div class="content">
          <div class="tagline">Look inside <?php echo $company['name']; ?></div>
          <?php echo vlinkto('<input type="button" class="button" value="View Company Profile" />', 'company', array('id' => $company['_id']->{'$id'}), true); ?></div>
        </div>
      </div>
    </div>
  </panel>
<?php } ?>
<panel class="results">
  <div class="content">
    <?php if (!is_null(vget('recent'))) { ?>
      <headline>Recent Listings</headline>
    <?php } ?>
    <?php
      function jobBlock($job) {
        $title = $job['title'];
        $company = $job['company'];
        $location = $job['location'];
        $desc = $job['desc'];
        $deadline = $job['deadline'];
        return "
          <div class=\"jobblock\">
            <div class=\"title\">$title | $company</div>
            <div class=\"desc\">$desc</div>
            <div class=\"info\">Deadline: $deadline</div>
          </div>
        ";
      }
      $jobs = vget('jobs');
      foreach ($jobs as $job) {
        echo vlinkto(jobBlock($job), 'job', array('id' => $job['_id']->{'$id'}));
      }
      if (count($jobs) == 0) {
        echo "No jobs matching your query. Try reducing your filters.";
      }
    ?>
  </div>
</panel>