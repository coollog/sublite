<style>
  .subletblock {
    text-align: left;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
    color: #000;
  }
  .subletblock:hover {
    opacity: 0.5;
  }
  .subletblock .title {
    font-size: 1.5em;
    color: #03596a;
    line-height: 40px;
  }
  .subletblock .info {
    color: #999;
    line-height: 40px;
  }
</style>

<panel class="sublets">
  <div class="content">
    <headline>Manage Sublet Listings</headline>
    <div>(Analytics Under Construction)</div>
    <?php
      function subletBlock($sublet) {
        $title = $sublet['title'];
        $location = $sublet['address'];
        if ($sublet['city'] != '') $location .= $sublet['city'];
        if ($sublet['state'] != '') $location .= $sublet['state'];
        $summary = strmax($sublet['summary'], 300);
        $price = $sublet['price'];
        $pricetype = $sublet['pricetype'];
        $publish = $sublet['publish'];
        if ($publish) $published = '<green>Public</green>';
        else          $published = '<red>Private (Publish to have listing show up in search results)</red>';
        return "
          <div class=\"subletblock\">
            <div class=\"title\">$title | $location</div>
            <div class=\"summary\">$summary</div>
            <div class=\"info\">Price: $price /$pricetype | $published</div>
          </div>
        ";
      }
      $sublets = View::get('sublets');
      foreach ($sublets as $sublet) {
        echo View::linkTo(subletBlock($sublet), 'editsublet', array('id' => $sublet['_id']->{'$id'}));
      }
      if ($sublets->count() == 0) {
        echo "<b style=\"font-size: 1.5em;\">Congratulations! You have completed your profile and are on your way to finding tenants for the summer. Just take a moment to complete your sublet listing(s) by clicking the button below and you'll be all set!</b><br /><br />" . View::linkTo('<input type="button" value="List Sublet" />', 'addsublet');
      }
    ?>
  </div>
</panel>