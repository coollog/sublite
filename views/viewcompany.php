<style>
  .company {
    margin: 40px 0;
    text-align: left;
    width: 100%;
  }
  .companytable {
    border-spacing: 10px;
    border-collapse: separate;
  }
  .companytable td {
    vertical-align: top;
  }
  .companytable td.col {
    width: 30%;
  }
  .companylogo {
    width: 150px;
    height: 150px;
  }
  .companyname {
    font-size: 1.5em;
    font-weight: 700;
    line-height: 2.5em;
  }
  .officephoto {
    background: <?php vecho('bannerphoto'); ?> no-repeat center center;
    background-size: cover;
  }
  .brief .info {
    padding: 40px;
    font-size: 0.8em;
  }
  .blurb {
    text-align: left;
    padding: 20px;
    border-top: 20px solid transparent;
    border-bottom: 10px solid transparent;
    font-size: 0.8em;
    line-height: 1.3em;
    margin-bottom: 20px;
  }
  .blurb subheadline {
    text-transform: uppercase;
    font-size: 2em;
    margin-top: 0;
    line-height: 1.2em;
    margin-bottom: 0.5em;
  }
  .blurb.pink { border-color: #ff2a5c; }
  .blurb.orange { border-color: #ff8a00; }
  .blurb.purple { border-color: #9d0277; }
  .blurb.cobalt { border-color: #035e75; }
  .blurb.blue { border-color: #01354a; }
</style>

<panel class="job">
  <div class="content">
    <?php if (vget('isme')) { ?>
      <a href="editcompany.php"><input type="button" value="Edit Company Profile" /></a>
    <?php } ?>

    <div class="companyinfo">
      <table class="companytable">
        <tr>
          <td class="brief">
            <div class="info">
              <img class="companylogo" src="<?php vecho('logophoto'); ?>" />
              <div class="companyname"><?php vecho('name'); ?></div>
              <?php 
                $industries = vget('industry');
                $last = $industries[count($industries) - 1];
                foreach ($industries as $industry) {
                  if ($industry == $last) echo $industry;
                  else echo "$industry, ";
                }
              ?><br />
              <?php vecho('size'); ?><br />
              Founded in <?php vecho('founded'); ?><br />
              <?php 
                $locations = vget('location');
                $last = $locations[count($locations) - 1];
                foreach ($locations as $location) {
                  if ($location == $last) echo $location;
                  else echo "$location, ";
                }
              ?><br />
            </div>
          </td>
          <td colspan="2" class="officephoto"></td>
        </tr>
        <tr>
          <td class="col">
            <?php
              function blurb($title, $color) {
                return "<div class=\"blurb $color\">
                  <subheadline>$title</subheadline>
                  {var}
                </div>";
              }
              vecho('desc', blurb('The Company', 'pink'));
              vecho('corevalues', blurb('Core Values', 'orange'));
              vecho('funfacts', blurb('Fun Facts', 'purple'));
              vecho('socialevent', blurb('Social Events', 'blue'));
            ?>
          </td>
          <td class="col">
            <?php
              vecho('colorscheme', blurb('Colorful Office', 'cobalt'));
              vecho('media', blurb(vget('name') . ' in the Media', 'orange'));
              vecho('employees', blurb('Employees Are Saying', 'purple'));
              vecho('perks', blurb('Top 3 Perks', 'pink'));
            ?>
          </td>
          <td class="col">
            <?php
              vecho('forfun', blurb('For Fun', 'purple'));
              vecho('talent', blurb('Hidden Talents', 'cobalt'));
              vecho('dresscode', blurb('Dress Code', 'pink'));
              vecho('freeanswer1', blurb(vget('freequestion1'), 'orange'));
              vecho('freeanswer2', blurb(vget('freequestion2'), 'blue'));
            ?>
          </td>
        </tr>
      </table>
    </div>

    <?php if (vget('isme')) { ?>
      <a href="editcompany.php"><input type="button" value="Edit Company Profile" /></a>
    <?php } ?>
  </div>
</panel>