<style>
  img {
    max-width: 100%;
    margin-bottom: 20px;
  }
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
    display: block;
    margin: 0 auto;
    background: url('<?php vecho('logophoto'); ?>') no-repeat center center;
    background-size: contain;
    margin-bottom: 1em;
  }
  .companyname {
    font-size: 2.5em;
    font-weight: 700;
    line-height: 1em;
    padding: 20px 80px;
    position: absolute;
    bottom: 40px;
    color: #fff;
    background: rgba(0,0,0,0.8);
    right: 0;
  }
  .officephoto {
    background: url('<?php vecho('bannerphoto'); ?>') no-repeat center center;
    background-size: cover;
    position: relative;
  }
  .brief .info {
    padding: 20px;
    font-size: 1em;
  }
  .blurb {
    text-align: left;
    padding: 20px;
    border-top: 20px solid transparent;
    border-bottom: 10px solid transparent;
    font-size: 0.9em;
    line-height: 1.3em;
    margin-bottom: 20px;
    white-space: pre-line;
  }
  .blurb subheadline {
    text-transform: uppercase;
    font-size: 3em;
    margin-top: -0.3em;
    line-height: 1em;
    margin-bottom: -0.3em;
    font-family: BebasNeue;
  }
  .blurb.pink { border-color: #ff2a5c; }
  .blurb.pink subheadline { color: #ff2a5c; }
  .blurb.orange { border-color: #ff8a00; }
  .blurb.orange subheadline { color: #ff8a00; }
  .blurb.purple { border-color: #9d0277; }
  .blurb.purple subheadline { color: #9d0277; }
  .blurb.cobalt { border-color: #035e75; }
  .blurb.cobalt subheadline { color: #035e75; }
  .blurb.blue { border-color: #01354a; }
  .blurb.blue subheadline { color: #01354a; }
</style>

<panel class="job">
  <div class="content">
    <?php if (vget('isme')) { ?>
      <a href="editcompany.php"><input type="button" value="Edit Company Profile" /></a><br /><br />
    <?php } ?>

    <div class="companyinfo">
      <table class="companytable">
        <tr>
          <td class="brief">
            <div class="info">
              <div class="companylogo"></div>
              <?php
                // $industries = vget('industry');
                // $last = $industries[count($industries) - 1];
                // foreach ($industries as $industry) {
                //   if ($industry == $last) echo $industry;
                //   else echo "$industry, ";
                // }
                vecho('industry');
              ?><br />
              <?php vecho('size'); ?><br />
              Founded in <?php vecho('founded'); ?><br />
              <?php
                // $locations = vget('location');
                // $last = $locations[count($locations) - 1];
                // foreach ($locations as $location) {
                //   if ($location == $last) echo $location;
                //   else echo "$location, ";
                // }
                vecho('location');
              ?><br /><br />
              <a href="search.php?bycompany=<?php vecho('name'); ?>"><input type="button" value="View Job Listings" /></a>
            </div>
          </td>
          <td colspan="2" class="officephoto">
              <div class="companyname"><?php vecho('name'); ?></div>
          </td>
        </tr>
        <tr>
          <?php
            global $pointer, $cols, $photos;
            $pointer = 0;
            $photos = vget('photos');
            $cols = array(array(), array(), array());
            function insertPhoto($index, $col, $pos) {
              global $cols, $photos;
              if ($index >= sizeof($photos)) return;
              if ($pos > sizeof($cols[$col])) return;
              $url = $photos[$index];
              $html = "<img src=\"$url\">";
              array_splice($cols[$col], $pos, 0, $html);
            }
            function blurb($name, $title, $color) {
              global $pointer, $cols;
              if (strlen($val = vget($name)) > 0) {
                $val = html_entity_decode($val);
                $cols[$pointer][] = "<div class=\"blurb $color\">
                                      <subheadline>$title</subheadline>
                                      $val
                                    </div>";
                $pointer = ($pointer + 1) % 3;
              }
            }
            blurb('desc', 'The Company', 'pink');
            blurb('corevalues', 'Core Values', 'orange');
            blurb('funfacts', 'Fun Facts', 'purple');
            blurb('socialevent', 'Social Events', 'blue');
            blurb('society', 'Giving Back to Society', 'orange');
            blurb('colorscheme', 'Colorful Office', 'cobalt');
            blurb('media', vget('name') . ' in the Media', 'orange');
            blurb('employees', 'Employees Are Saying', 'purple');
            blurb('perks', 'Top 3 Perks', 'pink');
            blurb('forfun', 'For Fun', 'purple');
            blurb('dessert', 'Type of Dessert', 'blue');
            blurb('talent', 'Hidden Talents', 'cobalt');
            blurb('dresscode', 'Dress Code', 'pink');
            blurb('freeanswer1', vget('freequestion1'), 'orange');
            blurb('freeanswer2', vget('freequestion2'), 'blue');

            insertPhoto(0, 0, 3);
            insertPhoto(1, 1, 2);
            insertPhoto(2, 2, 1);

          ?>
          <td class="col">
            <?php foreach ($cols[0] as $blurb) { echo $blurb; } ?>
          </td>
          <td class="col">
            <?php foreach ($cols[1] as $blurb) { echo $blurb; } ?>
          </td>
          <td class="col">
            <?php foreach ($cols[2] as $blurb) { echo $blurb; } ?>
          </td>
        </tr>
      </table>
    </div>


    <?php if (vget('isme')) { ?>
      <br /><br />
      <a href="editcompany.php"><input type="button" value="Edit Company Profile" /></a>
    <?php } ?>
  </div>
</panel>

<script>
  $(window).load(function() {
    function incrementalBalance() {
      var lengths = [];
      $('.col').each(function() {
        var toAdd =[];
        $(this).children().each(function() {
          toAdd.push($(this).outerHeight());
        });
        lengths.push(toAdd);
      });
      totalLengths = lengths.map(function(element) {
        return element.reduce(function(a,b){return a+b});
      });
      var max = totalLengths[0];
      var maxIndex = 0;
      var min = totalLengths[0];
      var minIndex = 0;
      for (var i = 1; i < totalLengths.length; ++i) {
        if (totalLengths[i] > max) {
          maxIndex = i;
          max = totalLengths[i]
        }
        if (totalLengths[i] < min) {
          minIndex = i;
          min = totalLengths[i]
        }
      }
      if (max - min < 50) return -1;
      var table = $('tr:last');
      var maxColumn = table.children().eq(maxIndex);
      var minColumn = table.children().eq(minIndex);
      var bestMin = min;
      var bestMinIndex = -1;
      for (var i = 0; i < lengths[maxIndex].length; ++i) {
        // Don't want to move pictures or "The Company"
        if ((maxIndex == 0 && i == 0) || maxColumn.children().eq(i).is('img')) continue;
        var height = lengths[maxIndex][i];
        if (Math.min(max - height, min + height) > bestMin + 50) {
          bestMin = Math.min(max - height, min + height);
          bestMinIndex = i;
        }
      }
      if (bestMinIndex != -1) {
        maxColumn.children().eq(bestMinIndex).detach().appendTo(minColumn);
        return 0;
      }
      return -1;
    };
    // Repeat this process 5 times (or until we don't see much improvement,
    // whichever comes first).
    for (var i = 0; i < 5; ++i) {
      if (incrementalBalance() == -1) break;
    }
  });
</script>