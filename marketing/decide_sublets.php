<?php
  /**
   * ONLY TO BE INCLUDED FROM INDEX.PHP!!!
   */

  require_once('decide.php');

  $decision = DeciderSublets::decide($collListings);
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
    <?php
      chartTop10(
        $decision['demand'],
        'chartDemand',
        'Top 10 Demand'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['searches'],
        'chartSearches',
        'Top 10 cities searched for'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['sublets'],
        'chartSublets',
        'Top 10 cities with sublets'
      );
    ?>
    <br />
  </charts>

  <?php require_once('goback.php'); ?>
</recommendation>