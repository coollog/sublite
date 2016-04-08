<!-- TODO: Refactor this with the other use of it -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

<style>
  panel.leaderboard .numdays {
    font-size: 1em;
    color: #035d75;
    text-transform: uppercase;
    background-color: #ffffff;
    border-color: #035d75;
    border-width: 1px;
  }
  panel.leaderboard .numdays:hover {
    color: #ffffff;
    background-color: #035d75;
  }
  panel.leaderboard .numdays:focus {
    outline: none;
  }

</style>

<script>

  function updateDiv(showDiv, totalDiv, days) {
      
    for(i = 1; i <= totalDiv; i++) {
      if(i == showDiv)
        document.getElementById("showHideDiv" + i).style.display = "block";
      else 
        document.getElementById("showHideDiv" + i).style.display = "none";
    }

    var title = document.getElementById("leaderboardTitle");
    title.innerHTML = "Sign-ups in past number of days: " + days;
  }

  function drawBarGraph(chart_id, my_data, ambassadors) {
    var key_array = new Array();
    var value_array = new Array();

    for(var key in my_data) {
      key_array.push(key);
      value_array.push(my_data[key]);
    }

    var tempAbbrevs = ["Columbia College Chicago", "University of Hartford", "New York University", "UC Berkeley", "University of Virginia"];
    var tempNewArray = new Array();
    for(var i = 0; i < tempAbbrevs.length; i++) {
      tempNewArray.push(tempAbbrevs[i] + " - " + ambassadors[i]);
    }
    key_array = tempNewArray;

    var data = {
      labels: key_array,
      datasets: [
        {
          label: "My dataset",
          fillColor: "rgba(151,187,205,0.5)",
          strokeColor: "rgba(151,187,205,0.8)",
          highlightFill: "rgba(151,187,205,0.75)",
          highlightStroke: "rgba(151,187,205,1)",
          data: value_array
        }
      ]
    };

    var ctx = document.getElementById(chart_id).getContext('2d');
    var myBarChart = new Chart(ctx).Bar(data);
  }

  function drawLineGraph(chart_id, my_data) {
    var key_array = new Array();
    var value_array = new Array();

    for(var i = 0; i < my_data.length; i++) {
      key_array.push(my_data[i][0]);
      value_array.push(my_data[i][1]);
    }

    var data = {
      labels: key_array,
      datasets: [
        {
          label: "My First dataset",
          fillColor: "rgba(151,187,205,0.2)",
          strokeColor: "rgba(151,187,205,1)",
          pointColor: "rgba(151,187,205,1)",
          pointStrokeColor: "#fff",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(151,187,205,1)",
          data: value_array
        }
      ]
    };

    var ctx = document.getElementById(chart_id).getContext('2d');
    var myLineChart = new Chart(ctx).Line(data);
  }
</script>

<?php
  $schoolCount = View::get('counts');
  $ambassadors = View::get('ambassadors');
  define('FOREVER', 0);
  define('SPRINT', 1);
?>

<panel class="leaderboard">
  <div class="content">
    <headline>Campus Rep Leaderboard</headline>

    <left>
      <headline class="small" id="leaderboardTitle">Sign-ups in past number of days:</headline>

      <center>
        <button class="numdays" onclick="return updateDiv(6, 6, &quot;7 Day Sprint&quot;);">7 Day Sprint</button>
        <button class="numdays" onclick="return updateDiv(1, 6, 7);">7</button>
        <button class="numdays" onclick="return updateDiv(2, 6, 30);">30</button>
        <button class="numdays" onclick="return updateDiv(3, 6, 90);">90</button>
        <button class="numdays" onclick="return updateDiv(4, 6, 180);">180</button>
        <button class="numdays" onclick="return updateDiv(5, 6, &quot;Forever&quot;);">Forever</button>
      </center>

      <div id="showHideDiv1">
        <canvas id="7days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("7days", <?php echo toJSON($schoolCount[7]); ?>, <?php echo toJSON($ambassadors);?>);
        </script>
      </div>

      <div id="showHideDiv2">
        <canvas id="30days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("30days", <?php echo toJSON($schoolCount[30]); ?>, <?php echo toJSON($ambassadors);?>);
        </script>
      </div>

      <div id="showHideDiv3">
        <canvas id="90days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("90days", <?php echo toJSON($schoolCount[90]); ?>, <?php echo toJSON($ambassadors);?>);
        </script>
      </div>

      <div id="showHideDiv4">
        <canvas id="180days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("180days", <?php echo toJSON($schoolCount[180]); ?>, <?php echo toJSON($ambassadors);?>);
        </script>
      </div>

      <div id="showHideDiv5">
        <canvas id="foreverdays" width="900" height="400"></canvas>
        <script>
          drawBarGraph("foreverdays", <?php echo toJSON($schoolCount[FOREVER]); ?>, <?php echo toJSON($ambassadors);?>);
        </script>
      </div>

      <div id="showHideDiv6">
        <canvas id="sprintdays" width="900" height="400"></canvas>
        <script>
          drawBarGraph("sprintdays", <?php echo toJSON($schoolCount[SPRINT]); ?>, <?php echo toJSON($ambassadors);?>);
        </script>
      </div>

      <script>
        updateDiv(5, 6, "Forever");
      </script>

    </left>
  </div>
</panel>