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
  showFilters {
    cursor: pointer;
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

    $('showFilters').click(function () {
      $('filters').slideDown(100, 'easeOutCubic');
      $(this).slideUp(100, 'easeOutCubic');
    });
      $('filters input[type=checkbox]').click(function () {
        Jobs.search(true);
      });
  });
</script>

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

<panel class="results">
  <div class="content">
    <?php if (!is_null(View::get('recent'))) { ?>
      <headline>Recent Listings</headline>
    <?php } ?>

    <filters class="hide div">
      <form>
        <input type="checkbox" name="jobtype" id="fulltime"
          value="fulltime" checked />
        <label for="fulltime"> Full-Time</label>
        <input type="checkbox" name="jobtype" id="parttime"
          value="fulltime" checked />
        <label for="parttime"> Part-Time</label>
        <input type="checkbox" name="jobtype" id="internship"
          value="internship" checked />
        <label for="internship"> Internship</label>
        <br />
        <input type="checkbox" name="salarytype" id="paid"
          value="paid" checked />
        <label for="paid"> Paid</label>
        <input type="checkbox" name="salarytype" id="unpaid"
          value="unpaid" checked />
        <label for="unpaid"> Unpaid/Other</label>
      </form>
    </filters>
    <a><showFilters class="hide div">Show Filters</showFilters></a>

    <jobs></jobs>

    <a><showMore>Show More</showMore></a>
  </div>
</panel>
