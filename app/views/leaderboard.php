<?php
  require_once($GLOBALS['dirpre']."views/LeaderboardCreater.php");
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<script src='app/assets/js/leaderboardGraphs.js'></script>
<script src='app/assets/js/buttons.js'></script>

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
  function draw_bar_graph(chart_id, my_data) {
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

  function draw_line_graph(chart_id, my_data) {
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

<panel class="leaderboard">
  <div class="content">
    <headline>Campus Rep Leaderboard</headline>

    <left>
      <headline class="small" id="leaderboardTitle">Sign-ups in past number of days:</headline>

      <?php
        $my_schools_days = array("Bentley University", "Eastern Michigan University");
        $my_schools_days_7_data = $CLeaderboardCreater->specific_school_count_past_number_days($my_schools_days, 7);
        $my_schools_days_30_data = $CLeaderboardCreater->specific_school_count_past_number_days($my_schools_days, 30);
        $my_schools_days_90_data = $CLeaderboardCreater->specific_school_count_past_number_days($my_schools_days, 90);
        $my_schools_days_180_data = $CLeaderboardCreater->specific_school_count_past_number_days($my_schools_days, 180);
        $my_schools_days_forever_data = $CLeaderboardCreater->specific_school_count_since_date($my_schools_days, 0);
      ?>
      <center>
        <button class="numdays" onclick="return showHide();">7</button>
        <button class="numdays" onclick="return showHide1();">30</button>
        <button class="numdays" onclick="return showHide2();">90</button>
        <button class="numdays" onclick="return showHide3();">180</button>
        <button class="numdays" onclick="return showHide4();">Forever</button>
      </center>

      <div id="showHideDiv">
        <canvas id="7days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("7days", <?php echo json_encode($my_schools_days_7_data);?>);
        </script>
      </div>

      <div id="showHideDiv1">
        <canvas id="30days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("30days", <?php echo json_encode($my_schools_days_30_data);?>);
        </script>
      </div>

      <div id="showHideDiv2">
        <canvas id="90days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("90days", <?php echo json_encode($my_schools_days_90_data);?>);
        </script>
      </div>

      <div id="showHideDiv3">
        <canvas id="180days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("180days", <?php echo json_encode($my_schools_days_180_data);?>);
        </script>
      </div>

      <div id="showHideDiv4">
        <canvas id="foreverdays" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("foreverdays", <?php echo json_encode($my_schools_days_forever_data);?>);
        </script>
      </div>

      <script>
        showHide4();
      </script>

    </left>
  </div>
</panel>