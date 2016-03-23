<style type="text/css">
  p {
    margin-left: 100px;
  }
  pre {
    text-align: left;
    font-size: .7em;
  }
</style>
<script>
$(function() {
  $(".getStats").click(function (){
    var method = this.id;
    var button = $("#"+method);
    $.getJSON("stats/"+method)
      .done(function(data) {
        console.log(data);
        if ($.isArray(data)) {
          // one textarea
          button.append(makeTextArea(data));
        } else {
          // jQuery object perhaps multiple textareas
          var content;
          for (var title in data) {
            content += "<h2>"+title+"</h2>";
            content += makeTextArea(data[title]);
          }
          button.append(content);
        }
      })
      .fail(function(data) {
        alert("Request failure.");
        console.log(data);
      });
    console.log("clicked a button!");
    console.log(this.id);
  });
});

function makeTextArea(lines) {
  var content = "<textarea>";
  for (var i = 0, n = lines.length; i < n; i++) {
    content += lines[i];
    content += "\n";
  }
  content += "</textarea>";
  return content;
}
</script>
<panel>
<div class = "content">
  <h1>SubLite Statistics</h1>
  <div id = "cumulative">
    <h2>Cumulative Stats</h2>
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
  <hr />
  <div>
    <h2>Recruiter Stats</h2>
    <button class ="getStats" id = "recruiters">Get Recruiter Stats</button>
  </div>
  <hr />
  <div>
    <h2>Student Stats</h2>
    <button class ="getStats" id = "students">Get Student Stats</button>
  </div>
  <hr />
  <div>
    <h2>Sublet Stats</h2>
    <button class ="getStats" id = "sublets">Get Sublet Stats</button>
  </div>
  <hr />
  <div>
    <h2>School Stats</h2>
    <button class ="getStats" id = "schools">Get School Stats</button>
  </div>
  <hr />
  <div>
    <h2>Message Stats</h2>
    <button class ="getStats" id = "messages">Get Message Stats</button>
  </div>
  <hr />
  <div>
    <h2>Application Stats</h2>
    <button class ="getStats" id = "applications">Get Application Stats</button>
  </div>
  <hr />
  <div id="update">
    <pre><?php var_dump(View::get('updateArray')['stats']); ?></pre>
    <pre><?php var_dump(View::get('updateArray')['cities']);?></pre>
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

</div>

</panel>
