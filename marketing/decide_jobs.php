<?php
  /**
   * ONLY TO BE INCLUDED FROM INDEX.PHP!!!
   */

  require_once('decide.php');

  $decision = DeciderJobs::decide($collJobs, $collCompanies);

  function priorityToText($priority) {
    $priorityMap = [
      1 => 'high',
      2 => 'medium',
      3 => 'low'
    ];
    return $priorityMap[$priority];
  }
?>

<title>Goal: Balance supply and demand for jobs.</title>

<?php require_once('commonhtml.php'); ?>

<link href="commondecide.css" rel="stylesheet" type="text/css">

<recommendation class="content">
  <h5>SubLite Marketing Automated Decision System</h5>
  <br /><br />
  <h1>Goal: Balance supply and demand for jobs.</h1>
  <br /><br />

  By analysis of our database on both the searches that were performed and the jobs
  that are available to apply to, the system calculated a demand score for each city
  and industry that was searched for. This demand score equals the amount
  of unique demand for a city, company, or industry minus the number of jobs
  available in each category.
  <br /><br />
  These cities should be targetted for marketing efforts to increase job listings:
  <br /><br />

  <table id="decisionCities">
    <tr>
      <th>Priority</th>
      <th>City, State</th>
    </tr>
    <?php
      $recommendationCities = $decision['recommendationCities'];
      foreach ($recommendationCities as $priority => $cityStateList) {
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
  These industries should be targetted for marketing efforts to increase job listings:
  <br /><br />

  <table id="decisionIndustries">
    <tr>
      <th>Priority</th>
      <th>Industries</th>
    </tr>
    <?php
      $recommendationIndustries = $decision['recommendationIndustries'];
      foreach ($recommendationIndustries as $priority => $industryList) {
        $industries = implode('<br>', $industryList);
    ?>
        <tr>
          <td><?php echo priorityToText($priority); ?></td>
          <td><?php echo $industries; ?></td>
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
      $top3Cities = array_slice($decision['demandCities'], 0, 3);
      $textList = [];
      foreach ($top3Cities as $cityState => $count) {
        $textList[] = "$cityState ($count)";
      }
      echo implode(' | ', $textList);
    ?>
  </top3>

  <br /><br />
  The top three industries that should be targetted, along with their relative demand
  scores are:
  <top3>
    <?php
      $top3Industries = array_slice($decision['demandIndustries'], 0, 3);
      $textList = [];
      foreach ($top3Industries as $industry => $count) {
        $textList[] = "$industry ($count)";
      }
      echo implode(' | ', $textList);
    ?>
  </top3>

  <br /><br />
  Below are the charts to see more concrete data:
  <charts>
    <?php
      chartTop10(
        $decision['demandCities'],
        'chartDemandCities',
        'Top 10 Location Demand'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['demandIndustries'],
        'chartDemandIndustries',
        'Top 10 Industry Demand'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['searchesCities'],
        'chartSearchesCities',
        'Top 10 cities searched for'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['searchesIndustries'],
        'chartSearchesIndustries',
        'Top 10 industries searched for'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['jobsCities'],
        'chartJobsCities',
        'Top 10 cities with jobs'
      );
    ?>
    <br /><br />
    <?php
      chartTop10(
        $decision['companyIndustries'],
        'chartCompanyIndustries',
        'Top 10 industries with jobs'
      );
    ?>
    <br />
  </charts>

  <?php require_once('goback.php'); ?>
</recommendation>