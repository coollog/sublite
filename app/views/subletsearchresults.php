<style>
  .list {
    text-align: left;
  }
  .subletlink {
    display: inline-block;
    margin: 10px;
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
  }
  .subletblock .info {
    color: #035d75;
    padding: 20px;
    box-sizing: border-box;
  }
  .subletblock .title {
    line-height: 1.5em;
    width: 75%;
    float: left;
    vertical-align: bottom;
  }
  .subletblock .proximity {
    font-size: 0.8em;
    float: right;
    vertical-align: bottom;
  }
  .subletblock .address {
    width: 60%;
    line-height: 1.5em;
    float: left;
  }
  .subletblock .price {
    font-weight: 700;
    font-size: 2em;
    letter-spacing: -0.5px;
    float: right;
  }
  .subletblock .pricetype {
    font-size: 0.5em;
    opacity: 0.5;
  }
</style>

<panel class="results">
  <div class="content">
    <?php if (!is_null(vget('recent'))) { ?>
      <headline>Recent Listings</headline>
    <?php } ?>
    <div class="list">
      <?php
        function subletBlock($sublet) {
          $photo = $sublet['photo'];
          $title = $sublet['title'];
          $address = $sublet['address'];
          $proximity = ($sublet['proximity'] == '') ? $sublet['proximity'] : 
            "<div class=\"proximity\">".$sublet['proximity'].' mi</div>';
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
                  $proximity
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
  </div>
</panel>