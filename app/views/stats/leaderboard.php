<!-- TODO: Refactor this with the other use of it -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
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
  function showHide() {
      var ele = document.getElementById("showHideDiv");
      var ele1 = document.getElementById("showHideDiv1");
      var ele2 = document.getElementById("showHideDiv2");
      var ele3 = document.getElementById("showHideDiv3");
      var ele4 = document.getElementById("showHideDiv4");
      ele.style.display = "block";
      ele1.style.display = "none";
      ele2.style.display = "none";
      ele3.style.display = "none";
      ele4.style.display = "none";

      var title = document.getElementById("leaderboardTitle");
      title.innerHTML = "Sign-ups in past number of days: 7";
  }

  function showHide1() {
      var ele = document.getElementById("showHideDiv");
      var ele1 = document.getElementById("showHideDiv1");
      var ele2 = document.getElementById("showHideDiv2");
      var ele3 = document.getElementById("showHideDiv3");
      var ele4 = document.getElementById("showHideDiv4");
      ele.style.display = "none";
      ele1.style.display = "block";
      ele2.style.display = "none";
      ele3.style.display = "none";
      ele4.style.display = "none";

      var title = document.getElementById("leaderboardTitle");
      title.innerHTML = "Sign-ups in past number of days: 30";
  }

  function showHide2() {
      var ele = document.getElementById("showHideDiv");
      var ele1 = document.getElementById("showHideDiv1");
      var ele2 = document.getElementById("showHideDiv2");
      var ele3 = document.getElementById("showHideDiv3");
      var ele4 = document.getElementById("showHideDiv4");
      ele.style.display = "none";
      ele1.style.display = "none";
      ele2.style.display = "block";
      ele3.style.display = "none";
      ele4.style.display = "none";

      var title = document.getElementById("leaderboardTitle");
      title.innerHTML = "Sign-ups in past number of days: 90";
  }

  function showHide3() {
      var ele = document.getElementById("showHideDiv");
      var ele1 = document.getElementById("showHideDiv1");
      var ele2 = document.getElementById("showHideDiv2");
      var ele3 = document.getElementById("showHideDiv3");
      var ele4 = document.getElementById("showHideDiv4");
      ele.style.display = "none";
      ele1.style.display = "none";
      ele2.style.display = "none";
      ele3.style.display = "block";
      ele4.style.display = "none";

      var title = document.getElementById("leaderboardTitle");
      title.innerHTML = "Sign-ups in past number of days: 180";
  }

  function showHide4() {
      var ele = document.getElementById("showHideDiv");
      var ele1 = document.getElementById("showHideDiv1");
      var ele2 = document.getElementById("showHideDiv2");
      var ele3 = document.getElementById("showHideDiv3");
      var ele4 = document.getElementById("showHideDiv4");
      ele.style.display = "none";
      ele1.style.display = "none";
      ele2.style.display = "none";
      ele3.style.display = "none";
      ele4.style.display = "block";

      var title = document.getElementById("leaderboardTitle");
      title.innerHTML = "Sign-ups in past number of days: Forever";
  }

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
        <button class="numdays" onclick="return showHide();">7</button>
        <button class="numdays" onclick="return showHide1();">30</button>
        <button class="numdays" onclick="return showHide2();">90</button>
        <button class="numdays" onclick="return showHide3();">180</button>
        <button class="numdays" onclick="return showHide4();">Forever</button>
      </center>

      <div id="showHideDiv">
        <canvas id="7days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("7days", <?php echo toJSON($schoolCount[7]); ?>);
        </script>
      </div>

      <div id="showHideDiv1">
        <canvas id="30days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("30days", <?php echo toJSON($schoolCount[30]); ?>);
        </script>
      </div>

      <div id="showHideDiv2">
        <canvas id="90days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("90days", <?php echo toJSON($schoolCount[90]); ?>);
        </script>
      </div>

      <div id="showHideDiv3">
        <canvas id="180days" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("180days", <?php echo toJSON($schoolCount[180]); ?>);
        </script>
      </div>

      <div id="showHideDiv4">
        <canvas id="foreverdays" width="900" height="400"></canvas>
        <script>
          draw_bar_graph("foreverdays", <?php echo toJSON($schoolCount[FOREVER]); ?>);
        </script>
      </div>

      <script>
        showHide4();
      </script>

    </left>
  </div>
</panel>