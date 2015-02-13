<style>
  .subletblock {
    text-align: left;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
    color: #000;
    border-spacing: 20px;
  }
  .subletblock:hover {
    opacity: 0.5;
  }
  .subletblock .title {
    font-size: 1.5em;
    color: #03596a;
    line-height: 40px;
  }
  .subletblock .photo {
    background: transparent no-repeat center center;
    background-size: contain;
    width: 180px;
  }
</style>

<panel class="results">
  <div class="content">
    <?php if (!is_null(vget('recent'))) { ?>
      <headline>Recent Listings</headline>
    <?php } ?>
    <?php
      function subletBlock($sublet) {
        $photo = $sublet['photo'];
        $title = $sublet['title'];
        $location = $sublet['location'];
        $proximity = $sublet['proximity'];
        $price = $sublet['price'];
        $pricetype = $sublet['pricetype'];
        return "
          <table class=\"subletblock\"><tr>
            <td class=\"photo\" style=\"background-image: url('$photo');\"></td>
            <td>
              <div class=\"title\">$title</div>
              <div class=\"location\">$location</div>
              <div class=\"proximity\">$proximity mi</div>
              <div class=\"price\">\$$price</div>
              <div class=\"pricetype\">$pricetype</div>
            </td>
          </tr></table>
        ";
      }
      $sublets = vget('sublets');
      foreach ($sublets as $sublet) {
        echo vlinkto(subletBlock($sublet), 'sublet', array('id' => $sublet['_id']->{'$id'}));
      }
      if (count($sublets) == 0) {
        echo "No sublets matching your query. Try reducing your filters.";
      }
    ?>
  </div>
</panel>