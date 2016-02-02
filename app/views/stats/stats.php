<style type="text/css">
  p {
    margin-left: 100px;
  }
  pre {
    text-align: left;
    font-size: .7em;
  }
</style>
<panel>
<div class = "content">
  <div id="update">
    <pre><?php var_dump(View::get('updateArray')['stats']); ?></pre>
    <pre><?php var_dump(View::get('updateArray')['cities']);?></pre>
  </div>
  <div id = "nojobs">
    <?php
    echo 'Recruiters:<br />
      <textarea style="width:800px; height: 400px;">';
    foreach (View::get('recruiterArray')['recruiterArray'] as $r) {
      echo "$r\n";
    }
    echo '</textarea>';
    ?>
  </div>

  <div id = "students">
    <?php
    $all = View::get('studentArray')['studentArray'];
    echo '<br />Students: '.count($all).'<br />
      <textarea style="width:800px; height: 200px;">';
    foreach ($all as $student) {
      echo "$student\n";
    }
    echo '</textarea>';

    $confirmed = View::get('confirmedStudentArray')['confirmedStudentArray'];
    echo '<br />Confirmed Students: '.count($confirmed).'<br />
      <textarea style="width:800px; height:200px;">';
    foreach ($confirmed as $email) {
      echo "$email\n";
    }
    echo '</textarea>';
    ?>
  </div>

  <div id="missingrecruiter">
    <?php
    $mr = View::get('missingRecruiterArray')['missingRecruiters'];
    echo '<br />Jobs nonexistent recruiter: '.count($mr).'<br />
     <textarea style="width:800px; height: 200px;">';
     foreach ($mr as $job) {
       $id = $job['_id'];
       $company = $job['company'];
       $recruiter = $job['recruiter'];
       echo "$id - c: $company, r: $recruiter\n";
     }
     echo '</textarea>';
    ?>
  </div>

  <div id = "subletsended2014">
    <?php
    $ss = View::get('subletsended2014Array')['subletsended2014'];
    echo '<br />Sublets with end dates before 1/1/2015:<br />
      <textarea style="width:800px; height: 200px;">';
    foreach ($ss as $s) {
      echo "$s\n";
    }
    echo '</textarea>';
    ?>
  </div>

  <div id = "unknownschools">
    <?php
    $domains = View::get('unknownSchoolsArray')['domains'];
    echo "<br />Unknown Schools: " . count($domains) . ' <br />
     <textarea style="width:800px; height: 200px;">';
    foreach ($domains as $d) {
      echo "$d\n";
    }
    echo '</textarea>';
    ?>
  </div>

  <div id = "cumulative">
    <?php
    $cumulative = View::get('cumulativeArray');
    $views = $cumulative['cumulativeviews'];
    $clicks = $cumulative['cumulativeclicks'];
    $claimedApps = $cumulative['claimedApplications'];
    $unclaimedApps = $cumulative['unclaimedApplications'];
    echo "<br />Total Jobs views: $views<br />Total Jobs clicks: $clicks <br />
          Total Claimed Applications: $claimedApps<br />Total Unclaimed Applications: $unclaimedApps <br />";
    ?>
  </div>

  <div id = "getmessageparticipants">
    <?php
    $plist = View::get('getMessageParticipantsArray')['messageparticipants'];
    echo "<br />Message Participants: " . count($plist) . ' <br />
     <textarea style="width:800px; height: 200px;">';
    foreach ($plist as $email => $data) {
      $type = $data['type'];
      $name = $data['name'];
      $count = $data['count'];
      echo "$email ($type) - $name - sent $count\n";
    }
    echo '</textarea>';
    ?>
  </div>
  <div id = "applications">
    <?php
      $applications = View::get('applicationsArray')['applicationsArray'];
      echo "<br />Jobs: " . count($applications) . ' <br />
      <textarea style="width:800px; height: 200px;">';
      foreach ($applications as $application) {
        echo "$application\n";
      }
      echo '</textarea>';
     ?>
  </div>

</div>

</panel>
