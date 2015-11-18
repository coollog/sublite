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
    <pre><?php var_dump(View::get('stats')); ?></pre>
    <pre><?php var_dump(View::get('cities'));?></pre>
  </div>
  <div id = "nojobs">
    <?php
    global $MRecruiter, $MJob, $MCompany; //get rid of these globals somehow?
      echo 'Recruiters who have posted jobs:<br />
        <textarea style="width:800px; height: 400px;">';
      foreach (View::get('recruiterEmailsWJ') as $r) {
        $email = $r['email'];
        $firstname = $r['firstname'];
        $lastname = $r['lastname'];
        $company = $r['company'];
        if (MongoId::isValid($company))
          $company = $MCompany->getName($company);
        echo "\"$email\",\"$firstname\",\"$lastname\",\"$company\"\n";
      }
      echo '</textarea>';
      echo '<br />Recruiters who have not posted jobs:<br />
        <textarea style="width:800px; height: 400px;">';
      foreach (View::get('recruiterEmails') as $email) {
        echo "$email\n";
      }
      echo '</textarea>';
      echo '<br />Recruiters who have not posted jobs but have made a company profile:<br />
        <textarea style="width:800px; height: 400px;">';
      foreach (View::get('recruiterEmailsWC') as $email) {
        echo "$email\n";
      }
      echo '</textarea>';
    ?>
  </div>
  <div id = "students">
    <?php
    $c = View::get('studentsConfirmedEmails');
    $u = View::get('studentsUnconfirmedEmails');
    $all = View::get('allStudents');
    echo '<br />Confirmed students: '.count($c).'<br />
      <textarea style="width:800px; height: 200px;">';
    foreach ($c as $email) {
      echo "$email\n";
    }
    echo '</textarea>';
    echo '<br />Unconfirmed students: '.count($u).'<br />
      <textarea style="width:800px; height: 200px;">';
    foreach ($u as $email) {
      echo "$email\n";
    }
    echo '</textarea>';
    echo '<br />All students: '.count($all).'<br />
     <textarea style="width:800px; height: 200px;">';
    foreach ($all as $student) {
      $firstname = $student['firstname'];
      $lastname = $student['lastname'];
      $email = $student['email'];
      echo "$firstname , $lastname , $email\n";

    }
    echo '</textarea>';
    ?>
  </div>

  <div id="missingrecruiter">
    <?php
    $mr = View::get('missingRecruiters');
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

  <div id = "recruiterbydate">
    <?php
    $rs = View::get('recruiterByDate');
    echo '<br />Recruiters with date of joining:<br />
     <textarea style="width:800px; height: 200px;">';
    foreach ($rs as $r) {
      echo "$r\n";
    }
    echo '</textarea>';
    ?>
  </div>

  <div id = "subletsended2014">
    <?php
    $ss = View::get('subletsended2014');
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
    $domains = View::get('unknownschoolsdomains');
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
    $views = View::get('cumulativeviews');
    $clicks = View::get('cumulativeclicks');
    echo "<br />Jobs views: $views<br />Jobs clicks: $clicks<br />";
    ?>
  </div>

  <div id = "getmessageparticipants">
    <?php
    $plist = View::get('messageparticipants');
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

</div>

</panel>