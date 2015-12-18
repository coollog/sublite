<?php
  /**
   * ONLY TO BE INCLUDED FROM INDEX.PHP!!!
   */

  require_once('decide.php');

  $email = $_POST['email'];
  $decision = DeciderStudent::decide($email);
?>

<title>Goal: Determine a student's preferences.</title>

<?php require_once('commonhtml.php'); ?>

<link href="commondecide.css" rel="stylesheet" type="text/css">

<style>
  prefs {
    font-weight: bold;
    text-transform: uppercase;
  }
</style>

<recommendation class="content">
  <h5>SubLite Marketing Automated Decision System</h5>
  <br /><br />
  <h1>Goal: Determine a student's preferences.</h1>
  <br /><br />

  By analysis of the searches that were performed by the student with email
  '<?php echo $email; ?>', the system has determined the following to be the
  student's preferences for subletting locations, job locations, and job industries.
  <br /><br />
  To cater to this student, these cities should be targetted for marketing
  efforts to increase sublet listings:
  <br /><br />

  <prefs>
    <?php
      if (count($decision['subletLocations']) == 0) {
        echo 'Not enough data to determine preferences in this category.';
      } else {
        $prefs = [];
        foreach ($decision['subletLocations'] as $cityState => $count) {
          $prefs[] = "$cityState ($count)";
        }
        echo implode('<br>', $prefs);
      }
    ?>
  </prefs>

  <br /><br />
  To cater to this student, these cities should be targetted for marketing
  efforts to increase job listings:
  <br /><br />

  <prefs>
    <?php
      if (count($decision['jobLocations']) == 0) {
        echo 'Not enough data to determine preferences in this category.';
      } else {
        $prefs = [];
        foreach ($decision['jobLocations'] as $cityState => $count) {
          $prefs[] = "$cityState ($count)";
        }
        echo implode('<br>', $prefs);
      }
    ?>
  </prefs>

  <br /><br />
  To cater to this student, these industries should be targetted for marketing
  efforts to increase job listings:
  <br /><br />

  <prefs>
    <?php
      if (count($decision['jobIndustries']) == 0) {
        echo 'Not enough data to determine preferences in this category.';
      } else {
        $prefs = [];
        foreach ($decision['jobIndustries'] as $industry => $count) {
          $prefs[] = "$industry ($count)";
        }
        echo implode('<br>', $prefs);
      }
    ?>
  </prefs>

  <?php require_once('goback.php'); ?>
</recommendation>