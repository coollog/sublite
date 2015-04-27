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
?>

<style type="text/css">
  .resultsmap {
    width: 25%;
    padding: 0;
    display: inline-block;
    box-sizing: border-box;
    height: 80vh;
    float: left;
  }
  #map-canvas{
    height: 100%;
    width: 100%;
  }
  .iwpic {
    width: 200px;
    height: 100px;
    margin: 0 auto;
    background: transparent no-repeat center center;
    background-size: cover;
    position: relative;
  }
  .iwprice {
    position: absolute;
    bottom: 10px;
    right: 10px;
    color: #fff;
  }

  .results {
    width: 75%;
  <?php if (is_null(vget('recent'))) { ?>
    padding: 0;
  <?php } ?>
    display: inline-block;
    box-sizing: border-box;
    max-height: 80vh;
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
    position: relative;
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

  @media (max-width: 1200px) {
    .resultsmap {
      width: 100%;
      display: block;
      float: none;
      height: 300px;
    }
    .results {
      display: block;
      width: 100%;
      float: none;
      max-height: none;
      overflow-y: visible;
    }
    .subletblock {
      width: 400px;
    }
    .subletlink {
      width: 400px;
    }
  }
  @media (max-width: 900px) {
    .subletblock {
      width: 100%;
    }
    .subletlink {
      width: 100%;
    }
  }
</style>

<?php if (is_null(vget('recent'))) { ?>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDORLARDVNHaHBSLZ0UG-1EGABk-IH2uq0&sensor=false"></script>
  <script type="text/javascript">
    function initialize() {
      var locations = [
        <?php foreach ($sublets as $sublet) { ?>
          {
            title: "<?php jsecho($sublet['title']); ?>",
            link: "sublet.php?id=<?php jsecho($sublet['_id']->{'$id'}); ?>",
            photo: "<?php jsecho($sublet['photo']); ?>",
            summary: "<?php jsecho($sublet['summary']); ?>",
            price: "<?php jsecho($sublet['price']); ?>",
            pricetype: "<?php jsecho($sublet['pricetype']); ?>",
            latitude: <?php echo $sublet['latitude']; ?>,
            longitude: <?php echo $sublet['longitude']; ?>
          },
        <?php } ?>
      ];

      var searchzone = new google.maps.LatLng(<?php vecho('latitude'); ?>, <?php vecho('longitude'); ?>);

      var styles = [
        {
          stylers: [
            { hue: "#035d75" },
            { saturation: -10 }
          ]
        },{
          featureType: "road",
          elementType: "geometry",
          stylers: [
            { lightness: 10},
            { visibility: "simplified" }
          ]
        },{
          featureType: "road.local",
          elementType: "labels",
          stylers: [
            { visibility: "off" }
          ]
        }
      ];

      var mapOptions = {
        center: searchzone,
        /*adjust number to change starting zoom size*/
        zoom: Math.round(14 - <?php vecho('maxProximity') ?> / 50 * 2),
        styles: styles
      };

      var map = new google.maps.Map(document.getElementById('map-canvas'),
          mapOptions);

      // set icon for marker
      var houseicon = '<?php echo $GLOBALS['dirpre']; ?>assets/gfx/map/marker.png';

      // create the markers and infowindows based on array location
      for (var i = 0; i < locations.length; i++) {
        var address = new google.maps.LatLng(locations[i].latitude, locations[i].longitude);

        var marker = new google.maps.Marker({
          position: address,
          map: map,
          icon: houseicon
        });

        var infowindow = new google.maps.InfoWindow();

        // creates infowindow when marker is clicked
        google.maps.event.addListener(marker, 'click', (function (marker, i) {
          return function() {
            //content for info window in html
            //change divs to format text
            infowindow.setContent(
              '<div class="iwcontent">' +
                '<h3 class="iwfirstHeading" class="firstHeading">' +
                  '<a href ="' + locations[i].link + '" target="_blank">' +
                    locations[i].title +
                  '</a>' +
                '</h3>' +
                '<div class="iwbodyContent">' +
                  '<div class="iwpic" style="background-image: url(\'' +
                    locations[i].photo +
                  '\');">' +
                    '<div class="iwprice">$' +
                      locations[i].price + '/' + locations[i].pricetype +
                    '</div>' +
                  '</div>' +
                  '<p>' +
                    locations[i].summary +
                  '</p>' +
                '</div>' +
              '</div>');

            infowindow.open(map,marker);
          }
        })(marker, i));
      }

      //clicking map will close opened infowindow
      google.maps.event.addListener(map, 'click', function() {
        infowindow.close(map, marker);
      });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
  </script>
  <panel class="resultsmap">
    <div id="map-canvas"></div>
  </panel>
<?php } ?>

<panel class="results">
  <?php if (vget('showSearch')) vpartial('subletsearchform', vget('data')); ?>

  <?php if (!is_null(vget('recent'))) { ?>
    <div class="content">
      <headline>Recent Listings</headline>
  <?php } ?>
      <div class="list">
        <?php
          if (isset($_GET['delay'])) vecho('delay', '<div style="text-align: center;"><i>Results returned in {var} ms</div><br />');

          foreach ($sublets as $sublet) {
            echo subletBlock($sublet);
          }
          if (count($sublets) == 0) {
            echo "No sublets matching your query. But don't fret! New sublets are being added regularly.";
          }
        ?>
      </div>
  <?php if (!is_null(vget('recent'))) { ?>
      <a href="?showMore">Show More</a>
      <?php if (vget('showMore')) { ?>
        <script>scrollTo('.subletblock', <?php vecho('showMore')-7; ?>);</script>
      <?php } ?>
    </div>
  <?php } ?>
</panel>
<div class="clear"></div>
