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

  function drawBarGraph(chart_id, my_data) {
    var key_array = new Array();
    var value_array = new Array();

    for(var key in my_data) {
      key_array.push(key);
      value_array.push(my_data[key]);
    }

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
  define('FOREVER', 0);
?>

<panel class="leaderboard">
  <div class="content">
    <headline>Campus Rep Leaderboard</headline>

    <left>
      <headline class="small" id="leaderboardTitle">Sign-ups in past number of days:</headline>

      <center>
        <button class="numdays" onclick="return updateDiv(1, 5, 7);">7</button>
        <button class="numdays" onclick="return updateDiv(2, 5, 30);">30</button>
        <button class="numdays" onclick="return updateDiv(3, 5, 90);">90</button>
        <button class="numdays" onclick="return updateDiv(4, 5, 180);">180</button>
        <button class="numdays" onclick="return updateDiv(5, 5, &quot;Forever&quot;);">Forever</button>
      </center>

      <div id="showHideDiv1">
        <canvas id="7days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("7days", <?php echo toJSON($schoolCount[7]); ?>);
        </script>
      </div>

      <div id="showHideDiv2">
        <canvas id="30days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("30days", <?php echo toJSON($schoolCount[30]); ?>);
        </script>
      </div>

      <div id="showHideDiv3">
        <canvas id="90days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("90days", <?php echo toJSON($schoolCount[90]); ?>);
        </script>
      </div>

      <div id="showHideDiv4">
        <canvas id="180days" width="900" height="400"></canvas>
        <script>
          drawBarGraph("180days", <?php echo toJSON($schoolCount[180]); ?>);
        </script>
      </div>

      <div id="showHideDiv5">
        <canvas id="foreverdays" width="900" height="400"></canvas>
        <script>
          drawBarGraph("foreverdays", <?php echo toJSON($schoolCount[FOREVER]); ?>);
        </script>
      </div>

      <script>
        updateDiv(5, 5, "Forever");
      </script>

    </left>
  </div>
</panel>