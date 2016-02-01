<?php
  error_reporting(E_ALL & ~E_STRICT);
  ini_set('display_errors', '1');

  require_once('pass.php'); // Imports the MongoURI for the database.
  require_once('geocode.php');

  require_once('DeciderSublets.php');
  require_once('DeciderJobs.php');
  require_once('DeciderStudent.php');

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
  ProcessSearch::init($searches);

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
  studentemail {
    display: block;
    text-align: center;
  }
  input[type=text] {
    border: 2px solid #ccc;
    padding: 1em;
    font-family: inherit;
    font-size: inherit;
    display: block;
    width: 80%;
  }

  emaillist {
    display: block;
    overflow-y: auto;
    max-height: 400px;
    margin: 2em;
    border: 2px solid #eee;
    padding: 2em;
  }

  label {

  }
</style>

<choosegoal class="content">
  <h5>SubLite Marketing Automated Decision System</h5>
  <br /><br />
  <h1>What is your goal?</h1>

  <form method="post" novalidate>
    <buttons>
      <button type="submit" name="goal" value="sublets">
        Balance supply and demand for sublets.
      </button>

      <button type="submit" name="goal" value="jobs">
        Balance supply and demand for jobs.
      </button>

      <button id="buttonStudent">
        Determine a student's preferences.
      </button>
    </buttons>
    <studentemail>
      <input type="text" name="email" placeholder="Email address of student" required />
      <button name="goal" value="student">
        Determine preferences.
      </button>
      <button id="buttonBack">
        I have a different goal.
      </button>

      <br /><br />
      <label for="emaillist">See list of students</label>
      <emaillist>
        <?php
          $emailsHash = ProcessSearch::getEmails();
          $textList = [];
          foreach ($emailsHash as $email => $count) {
            $textList[] = "$email ($count)";
          }
          echo implode('<br>', $textList);
        ?>
      </emaillist>
    </studentemail>
    <div id="loading">
      Entering your preferences into the automated decision system...
    </div>
  </form>
</choosegoal>

<script>
  $('form').submit(function() {
    $('buttons, studentemail').hide();
    $('#loading').show();
    return true;
  });

  $('#buttonStudent').click(function () {
    event.preventDefault();
    $('buttons').hide();
    $('studentemail').show();
  });
  $('#buttonBack').click(function () {
    event.preventDefault();
    $('buttons').show();
    $('studentemail').hide();
  });
  $('studentemail').hide();

  $('label[for=emaillist]').click(function () {
    $('emaillist').slideToggle(100);
  });
  $('emaillist').hide();
</script>