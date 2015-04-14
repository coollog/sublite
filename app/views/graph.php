<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

Students:<br />
<canvas id="chart1" width="800" height="400"></canvas>
<br />
Students by Day:<br />
<canvas id="chart3" width="800" height="400"></canvas>
<br />
Messages:<br />
<canvas id="chart2" width="800" height="400"></canvas>

<script>
  var options = {
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 5,
  };

  var data1 = {
    labels: [
      <?php
        $data = vget('students');
        foreach ($data as $label => $count) {
          $label = date('M, y', ($label+1)*3600*24*30.2);
          echo "'$label',";
        }
      ?>
    ],
    datasets: [
      {
        label: "Students",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
          <?php
            $data = vget('students');
            foreach ($data as $count) {
              echo "'$count',";
            }
          ?>
        ]
      }
    ]
  };

  var data2 = {
    labels: [
      <?php
        $data = vget('msgs');
        foreach ($data as $label => $count) {
          $label = date('M d, y', ($label+1)*3600*24);
          echo "'$label',";
        }
      ?>
    ],
    datasets: [
      {
        label: "Messages",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
          <?php
            $data = vget('msgs');
            foreach ($data as $count) {
              echo "'$count',";
            }
          ?>
        ]
      }
    ]
  };

  var data3 = {
    labels: [
      <?php
        $data = vget('studentsday');
        $n = 0;
        foreach ($data as $label => $count) {
          $label = date('M d, y', ($label+1)*3600*24);
          if ($n % (int)(count($data)/30) == 0) echo "'$label',";
          else echo "'',";
          $n ++;
        }
      ?>
    ],
    datasets: [
      {
        label: "Students By Day",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
          <?php
            $data = vget('studentsday');
            foreach ($data as $count) {
              echo "'$count',";
            }
          ?>
        ]
      }
    ]
  };

  var ctx1 = $("#chart1").get(0).getContext("2d");
  var chart1 = new Chart(ctx1).Line(data1, options);

  var ctx2 = $("#chart2").get(0).getContext("2d");
  var chart2 = new Chart(ctx2).Line(data2, options);

  var ctx3 = $("#chart3").get(0).getContext("2d");
  var chart3 = new Chart(ctx3).Line(data3, options);
</script>

<?php if (isset($_GET['map'])) { ?>
  <br />
  Search Map:<br />
  <canvas id="chart4" width="800" height="400"></canvas>
  <script>
    var data4 = {
      labels: ["January", "February", "March", "April", "May", "June", "July"],
      datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        }
      ]
    };
    var ctx4 = $("#chart4").get(0).getContext("2d");
    var chart4 = new Chart(ctx4).Bar(data4, options);
  </script>
<?php } ?>