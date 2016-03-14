<style>
  .jobblock {
    text-align: left;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
    color: #000;
    border-spacing: 20px;
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
  .jobblock .img {
    background: transparent no-repeat center center;
    background-size: contain;
    width: 180px;
  }
</style>

<?php if (!is_null($company = View::get('showCompany'))) { ?>
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
          <?php echo View::linkto('<input type="button" class="button" value="View Company Profile" />', 'company', array('id' => $company['_id']->{'$id'}), true); ?></div>
        </div>
      </div>
    </div>
  </panel>
<?php } ?>

<templates>
  <jobtemplate>
    <a href="<?php View::echoLink('jobs/job?id={_id}'); ?>" target="_blank">
      <table class="jobblock"><tr>
        <td class="img" style="background-image: url('{logo}');"></td>
        <td>
          <div class="title">{title} | {company}</div>
          <div class="desc">{desc}</div>
          <div class="info">Deadline: {deadline}</div>
        </td>
      </tr></table>
    </a>
  </jobtemplate>
</templates>

<script>
  function loadContent(id, data, callback) {
    var route = '<?php View::echoLink('jobs/search/ajax/'); ?>' + id;

    $.post(route, data, function (data) {
      console.log("'" + route + "' returned with:");
      console.log(data);
      data = JSON.parse(data);
      callback(data);
    });
  }

  var Jobs = {
    container: 'jobs',
    Recent: {
      skip: 0,
      count: 5,
      load: function (initial) {
        if (initial) $(Jobs.container).text('Loading recent jobs...');
        loadContent('recent', {
          skip: this.skip,
          count: this.count
        }, function (data) {
          if (initial) Jobs.clear();
          Jobs.addMulti(data.jobs);
        });
      }
    },
    clear: function () {
      $(this.container).text('');
    },
    addMulti: function (data) {
      data.forEach(function (job) {
        var html = Templates.use('jobtemplate', job);
        $(this.container).append(html);
      });
    }
  };

  $(function () {
    // Templates.init();

    // Jobs.Recent.load(true);
  });
</script>

<panel class="results">
  <div class="content">
    <?php if (!is_null(View::get('recent'))) { ?>
      <headline>Recent Listings</headline>
    <?php } ?>

    <jobs></jobs>
    <?php if (!is_null(View::get('recent')) && View::get('recent')) { ?>
      <a href="?showMore">Show More</a>
      <?php if (View::get('showMore')) { ?>
        <script>scrollTo('.jobblock', <?php View::echof('showMore'); ?>-7);</script>
      <?php } ?>
    <?php } ?>
  </div>
</panel>
