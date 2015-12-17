<?php
  /**
   * ONLY TO BE INCLUDED FROM INDEX.PHP!!!
   */

  $decision = DeciderSublets::decide($collListings, $searches);
?>

<title>Goal: Balance supply and demand for sublets.</title>

<?php require_once('commonhtml.php'); ?>

<link href="commondecide.css" rel="stylesheet" type="text/css">

<recommendation class="content">
  By analysis of our database on both the searches that were performed and the sublets
  that are available for rent, the system calculated a demand score for each city that
  was searched for. This demand score equals the amount of unique demand for a city
  minus the number of sublets available in that city.
  <br /><br />
  These cities should be targetted for marketing efforts to increase sublet listings:
  <br /><br />

  <table id="decision">
    <tr>
      <th>Priority</th>
      <th>City, State</th>
    </tr>
    <?php
      function priorityToText($priority) {
        $priorityMap = [
          1 => 'high',
          2 => 'medium',
          3 => 'low'
        ];
        return $priorityMap[$priority];
      }
      $recommendation = $decision['recommendation'];
      foreach ($recommendation as $priority => $cityStateList) {
        $cityStates = implode('<br>', $cityStateList);
    ?>
        <tr>
          <td><?php echo priorityToText($priority); ?></td>
          <td><?php echo $cityStates; ?></td>
        </tr>
    <?php
      }
    ?>
  </table>

  <br /><br />
  The top three cities that should be targetted, along with their relative demand
  scores are:
  <top3>
    <?php
      $top3 = array_slice($decision['demand'], 0, 3);
      $textList = [];
      foreach ($top3 as $cityState => $count) {
        $textList[] = "$cityState ($count)";
      }
      echo implode(', ', $textList);
    ?>
  </top3>

  <br /><br />
  Below are the charts to see more concrete data:
  <charts>
    <label for="chartDemand">Top 10 Demand</label>
    <canvas id="chartDemand"></canvas>
    <br /><br />
    <label for="chartSearches">Top 10 cities searched for</label>
    <canvas id="chartSearches"></canvas>
    <br /><br />
    <label for="chartSublets">Top 10 cities with sublets</label>
    <canvas id="chartSublets"></canvas>
    <br />
  </charts>
</recommendation>

<script>
  var options = {
    pointHitDetectionRadius : 5,
  };

  var dataDemand = {
    labels: [
      <?php
        $demand10 = array_slice($decision['demand'], 0, 10);
        foreach ($demand10 as $cityState => $count) {
          echo "'$cityState', ";
        }
      ?>
    ],
    datasets: [
      {
        label: "City, State",
        fillColor: "rgba(220,220,220,0.2)",
        data: [
          <?php
            foreach ($demand10 as $cityState => $count) {
              echo "'$count', ";
            }
          ?>
        ]
      }
    ]
  };

  var dataSearches = {
    labels: [
      <?php
        $searches10 = array_slice($decision['searches'], 0, 10);
        foreach ($searches10 as $cityState => $count) {
          echo "'$cityState', ";
        }
      ?>
    ],
    datasets: [
      {
        label: "City, State",
        fillColor: "rgba(220,220,220,0.2)",
        data: [
          <?php
            foreach ($searches10 as $cityState => $count) {
              echo "'$count', ";
            }
          ?>
        ]
      }
    ]
  };

  var dataSublets = {
    labels: [
      <?php
        $sublets10 = array_slice($decision['sublets'], 0, 10);
        foreach ($sublets10 as $cityState => $count) {
          echo "'$cityState', ";
        }
      ?>
    ],
    datasets: [
      {
        label: "City, State",
        fillColor: "rgba(220,220,220,0.2)",
        data: [
          <?php
            foreach ($sublets10 as $cityState => $count) {
              echo "'$count', ";
            }
          ?>
        ]
      }
    ]
  };

  var ctxDemand = $("#chartDemand").get(0).getContext("2d");
  var chartDemand = new Chart(ctxDemand).Line(dataDemand, options);

  var ctxSearches = $("#chartSearches").get(0).getContext("2d");
  var chartSearches = new Chart(ctxSearches).Line(dataSearches, options);

  var ctxSublets = $("#chartSublets").get(0).getContext("2d");
  var chartSublets = new Chart(ctxSublets).Line(dataSublets, options);
</script>

<?php require_once('goback.php'); ?>