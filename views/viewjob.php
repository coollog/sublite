<style>
  panel.main {
    background: url('<?php vecho("companybanner") ?>') no-repeat center center;
    background-size: cover;
    display: table;
    height: 200px;
    padding-bottom: 0;
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
  .jobinfo {
    margin: 40px 0;
    text-align: left;
    width: 100%;
  }
  .jobtable {
    border-spacing: 10px;
    border-collapse: separate;
  }
  .jobtable td {
    vertical-align: top;
  }
  .jobtable td.col {
    width: 30%;
  }
  .icon {
    height: 100px;
    background: transparent no-repeat center left;
    background-size: contain;
    display: table;
    padding-left: 100px;
    width: 300px;
  }
  .icon subheadline {
    color: #000;
  }
</style>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">Look inside <?php vecho('companyname'); ?></div>
        <?php echo vlinkto('<input type="button" class="button" value="View Company Profile" />', 'company', array('id' => vget('companyid')), true); ?></div>
      </div>
    </div>
  </div>
</panel>
<panel class="job">
  <div class="content">
    <headline><?php vecho('title'); ?></headline>
    <a href="<?php vecho('link'); ?>" target="_blank"><input type="button" value="Apply Now" /></a>

    <div class="jobinfo">
      <table class="jobtable">
        <tr>
          <td style="width: 60%;">
            <div style="overflow: hidden; width: 100%;">
              <?php vecho('desc'); ?>
              <subheadline>Requirements</subheadline>
              <?php vecho('requirements'); ?>
            </div>
          </td>
          <td style="width: 30%;" style="vertical-align: middle;">
            
            <div class="icon" style="background-image: url('assets/gfx/deadlineico.png');">
              <div class="cell">
                <subheadline><?php vecho('deadline'); ?></subheadline>
                <small>Deadline of application</small>
              </div>
            </div>
            <div class="icon" style="background-image: url('assets/gfx/locationico.png');">
              <div class="cell">
                <subheadline><?php vecho('location'); ?></subheadline>
                <small>Location</small>
              </div>
            </div>
            <div class="icon" style="background-image: url('assets/gfx/durationico.png');">
              <div class="cell">
                <subheadline><?php vecho('duration'); ?> weeks</subheadline>
                <small>Duration</small>
              </div>
            </div>
            <div class="icon" style="background-image: url('assets/gfx/salaryico.png');">
              <div class="cell">
                <subheadline>$<?php vecho('salary'); ?> / <?php vecho('salarytype'); ?></subheadline>
                <small>Compensation</small>
              </div>
            </div>

          </td>
        </tr>
      </table>
    </div>

    <a href="<?php vecho('link'); ?>" target="_blank"><input type="button" value="Apply Now" /></a>
  </div>
</panel>