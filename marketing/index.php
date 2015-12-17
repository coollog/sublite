<?php
  error_reporting(E_ALL & ~E_STRICT);
  ini_set('display_errors', '1');

  require_once('pass.php'); // Imports the MongoURI for the database.
  require_once('geocode.php');

  require_once('DeciderSublets.php');
  require_once('DeciderJobs.php');

  // Connect to the databases.
  $m1 = new MongoClient($dbUri1);
  $m2 = new MongoClient($dbUri2);
  $db1 = $m1->sublite;
  $db2 = $m2->subliteinternships;
  $collListings = $db1->listings;
  $collJobs = $db2->jobs;
  $collCompanies = $db2->companies;
  $collGeocodes = $db1->geocodes;
  $collGeocodes->createIndex(['location' => 1]);

  GeocodeModel::init($collGeocodes);

  // Get the search data.
  $searches0 = $db2->app->findOne(['_id' => 'searches']);
  $searches1 = $db2->app->findOne(['_id' => 'searches1']);
  $searches2 = $db2->app->findOne(['_id' => 'searches2']);

  $searches = array_merge($searches0, $searches1, $searches2);

  if (isset($_POST['goal'])) {
    switch ($_POST['goal']) {
      case 'sublets': require_once('decide_sublets.php'); break;
      case 'jobs': require_once('decide_jobs.php'); break;
      case 'student': require_once('decide_student.php'); break;
    }
    exit();
  }
?>

<?php require_once('commonhtml.php'); ?>

<style>
  #loading {
    display: none;
    font-size: 1.5em;
  }
</style>

<choosegoal class="content">
  <h1>What is your goal?</h1>

  <form method="post">
    <buttons>
      <button type="submit" name="goal" value="sublets">
        Balance supply and demand for sublets.
      </button>

      <button type="submit" name="goal" value="jobs">
        Balance supply and demand for jobs.
      </button>

      <button type="submit" name="goal" value="student">
        Determine a student's preferences.
      </button>
    </buttons>
    <div id="loading">
      Entering your preferences into the automated decision system...
    </div>
  </form>
</choosegoal>

<script>
  $('form').submit(function() {
    $('buttons').hide();
    $('#loading').show();
    return true;
  });
</script>