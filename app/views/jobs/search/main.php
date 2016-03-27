<style>
  panel.main {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/how.jpg') no-repeat center center;
    background-size: cover;
    display: table;
    height: 150px;
  }
  panel.main .banner {
    padding: 30px 0;
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
  }
  panel.main .button:hover {
    color: #fff;
  }
  panel.search {
    background: #efecdb;
    padding: 20px 0;
  }
</style>

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
    skip: 0,
    count: 5,
    state: 'recent', // Loading recent jobs or searching?
    query: {},
    search: function (initial, callback) {
      $('.results headline').hide();
      this.state = 'search';
      this.count = 25;
      if (initial) {
        this.query = formJSON('.search form');
        // Filters
        this.appendSearchFilters();
      }
      this.load(this.query, initial, callback);
    },
    load: function (query, initial, callback) {
      // Load page of jobs by empty search (recent) or search query (search).
      if (this.state == 'recent') {
        var loadText = 'Loading recent jobs...';
      } else {
        var loadText = 'Loading jobs...';
      }

      if (initial) {
        this.skip = 0;
        $(Jobs.container).text(loadText);
      }
      loadContent('search', {
        query: query,
        skip: Jobs.skip,
        count: Jobs.count
      }, function (data) {
        if (initial) {
          $(Jobs.container).text('');
          if (data.jobs.length == 0) {
            $(Jobs.container).text('No jobs found.');
          }
        }
        if (data.more) $('showMore').show();
        else $('showMore').hide();
        Jobs.addMulti(data.jobs);

        if (typeof callback !== 'undefined') callback(data);
      });
    },
    addMulti: function (data) {
      var self = this;
      data.forEach(function (job) {
        var html = Templates.use('jobtemplate', job);
        $(self.container).append(html);
      });
      this.skip += this.count;
    },
    appendSearchFilters: function () {
      var jobTypes = [];
      $('input[name=jobtype]').each(function () {
        if ($(this).is(':checked')) jobTypes.push($(this).val());
      });
      if (jobTypes.length > 0) this.query.jobtype = { $in: jobTypes };
      else this.query.jobtype = null;

      var salaryTypes = [];
      $('input[name=salarytype]').each(function () {
        if ($(this).is(':checked')) salaryTypes.push($(this).val());
      });
      if (salaryTypes.length > 0) this.query.salarytype = salaryTypes;
      else this.query.salarytype = null;
    }
  };

  $(function () {
    Templates.init();

    $('input[name=search]').click(function () {
      $(this).prop('disabled', true);

      var self = this;
      Jobs.search(true, function () {
        $(self).prop('disabled', false);
        if (!$('filters').is(':visible')) $('showFilters').show();
      });
      scrollTo('.results');
    });

    $('.searchScroll').click(function() {
      scrollTo('.search');
    });
  });
</script>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <?php if (View::get('showSearch')) { ?>
          <script>
            $(function () {
              Jobs.load({}, true);
            });
          </script>

          <div class="tagline">Job Search, Reorganized</div>
          <input type="button" class="button searchScroll" value="Search for Jobs" />
        <?php } else if (!is_null($company = View::get('showCompany'))) { ?>
          <style>
            panel.main {
              background: url('<?php echo $company['bannerphoto']; ?>') no-repeat center center;
              height: 200px;
            }
            panel.main .banner {
              background: rgba(0, 0, 0, 0.5);
            }
            panel.main .button {
              box-shadow: 2px 2px 0px #035d75;
            }
          </style>

          <script>
            $(function () {
              Jobs.search(true);
            });
          </script>

          <div class="tagline">Look inside <?php echo $company['name']; ?></div>
          <?php
            echo View::linkTo(
              '<input type="button" class="button" value="View Company Profile" />',
              'company',
              [ 'id' => $company['_id']->{'$id'} ],
              true
            );
          ?>
        <?php } else if (!is_null($recruiterId = View::get('recruiterId'))) { ?>
          <div class="tagline">
            Jobs by <?php View::echof('recruiterName'); ?>
          </div>
          <a href="<?php View::echoLink("jobs/recruiter?id=$recruiterId"); ?>">
            <input type="button" class="button" value="View Recruiter Profile" />
          </a>

          <script>
            $(function () {
              Jobs.search(true);
            });
          </script>
        <?php } ?>
      </div>
    </div>
  </div>
</panel>