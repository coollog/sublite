<style>
  .results {
    width: 75%;
    display: inline-block;
    box-sizing: border-box;
    max-height: 1000px;
    overflow-y: scroll;
    float: right;
  }

  <?php if (!is_null(vget('recent'))) { ?>
    .results {
      width: 100%;
      display: block;
      float: none;
      max-height: none;
      overflow-y: visible;
    }
  <?php } ?>

  .list {
    text-align: left;
  }
  .subletlink {
    display: inline-block;
    margin: 0 10px;
  }
  .subletblock {
    text-align: left;
    width: 400px;
  }
  .subletblock:hover {
    opacity: 0.5;
  }
  .subletblock .photo {
    background: transparent no-repeat center center;
    background-size: cover;
    padding: 10px 20px;
    height: 300px;
    vertical-align: bottom;
    color: #fff;
    position: relative;
  }
  .subletblock .info {
    color: #035d75;
    padding: 20px 20px 10px 20px;
    box-sizing: border-box;
  }
  .subletblock .title {
    line-height: 1.5em;
    width: 75%;
    vertical-align: bottom;
  }
  .subletblock .proximity {
    font-size: 0.8em;
    vertical-align: bottom;
    position: absolute;
    bottom: 10px;
    right: 20px;
  }
  .subletblock .address {
    width: 60%;
    line-height: 1.5em;
    float: left;
    font-size: 0.9em;
  }
  .subletblock .price {
    font-weight: 700;
    font-size: 2em;
    line-height: 1em;
    letter-spacing: -0.5px;
    float: right;
  }
  .subletblock .pricetype {
    font-size: 0.5em;
    opacity: 0.5;
  }

  @media (max-width: 1000px) {
    .results {
      display: block;
      width: 100%;
      float: none;
      max-height: none;
      overflow-y: visible;
    }
    .subletblock {
      width: 100%;
    }
    .subletlink {
      width: 100%;
    }
  }
</style>

<panel class="results">
  <?php if (!is_null(vget('recent'))) { ?>
    <headline>Recent Listings</headline>
  <?php } ?>
  <div class="list">
    <?php if (isset($_GET['delay'])) vecho('delay', '<div style="text-align: center;"><i>Results returned in {var} ms</div><br />'); ?>
    <?php
      function subletBlock($sublet) {
        $photo = $sublet['photo'];
        $title = $sublet['title'];
        $address = $sublet['address'];
        $proximity = ($sublet['proximity'] == '') ? $sublet['proximity'] : 
          round($sublet['proximity'], 1).' mi';
        $price = $sublet['price'];
        $pricetype = $sublet['pricetype'];
        return "
          <a class=\"subletlink\" href=\"sublet.php?id=".$sublet['_id']->{'$id'}."\">
            <table class=\"subletblock\">
              <tr><td class=\"photo\" style=\"background-image: url('$photo');\">
                <div class=\"title\">$title</div>
                <div class=\"proximity\">$proximity</div>
              </td></tr>
              <tr><td class=\"info\">
                <div class=\"address\">$address</div>
                <div class=\"price\">\$$price<div class=\"pricetype\">/$pricetype</div></div>
              </td></tr>
            </table>
          </a>
        ";
      }
      $sublets = vget('sublets');
      foreach ($sublets as $sublet) {
        echo subletBlock($sublet);
      }
      if (count($sublets) == 0) {
        echo "No sublets matching your query. Try reducing your filters.";
      }
    ?>
  </div>
</panel>
<div class="clear"></div>