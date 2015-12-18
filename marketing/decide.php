<?php
  function loadChartToggle() {
    static $loadedChartToggle = false;

    if ($loadedChartToggle) return;

    $loadedChartToggle = true;

?>
    <script>
      $(function () {
        $('charts label').click(function () {
          var id = $(this).attr('for');
          $('#' + id).slideToggle(100);
        });

        $('charts canvas').hide();
      });
    </script>
<?php
  }

  function chartTop10(array $countHash, $chartId, $chartTitle) {
    loadChartToggle();
?>
    <label for="<?php echo $chartId; ?>"><?php echo $chartTitle; ?></label>
    <canvas id="<?php echo $chartId; ?>"></canvas>
    <script>
      (function () {
        var options = {
          pointHitDetectionRadius : 5,
        };
        var data = {
          labels: [
            <?php
              $top10 = array_slice($countHash, 0, 10);
              foreach ($top10 as $key => $count) {
                if (strlen($key) > 15) {
                  $key = substr($key, 0, 15) . '...';
                }
                echo "'$key', ";
              }
            ?>
          ],
          datasets: [
            {
              fillColor: "rgba(220,220,220,0.2)",
              data: [
                <?php
                  foreach ($top10 as $key => $count) {
                    echo "'$count', ";
                  }
                ?>
              ]
            }
          ]
        };

        var ctx = $("#<?php echo $chartId; ?>").get(0).getContext("2d");
        var chart = new Chart(ctx).Line(data, options);
      })();
    </script>
<?php
  }
?>