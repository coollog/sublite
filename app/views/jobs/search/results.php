<style>
  jobs {
    display: block;
  }
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
  showMore {
    cursor: pointer;
    display: none;
  }
</style>

<templates>
  <jobtemplate>
    <a href="<?php View::echoLink('jobs/job?id={_id}'); ?>" target="_blank">
      <table class="jobblock"><tr>
        <td class="img" style="background-image: url('{logophoto}');"></td>
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
  $(function () {
    $('showMore').click(function () {
      if (Jobs.state == 'recent') Jobs.load({}, false);
      else Jobs.search(false);
    });
  });
</script>

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

    <a><showMore>Show More</showMore></a>
  </div>
</panel>
