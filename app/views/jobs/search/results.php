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

<panel class="results">
  <div class="content">
    <?php if (!is_null(View::get('recent'))) { ?>
      <headline>Recent Listings</headline>
    <?php } ?>

    <jobs></jobs>

    <a><showMore>Show More</showMore></a>
  </div>
</panel>
