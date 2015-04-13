<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

Students:<br />
<canvas id="chart1" width="800" height="400"></canvas>
<br />
Messages:<br />
<canvas id="chart2" width="800" height="400"></canvas>

<script>
  var options = {

    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,

    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,

    //Boolean - Whether the line is curved between points
    bezierCurve : true,

    //Number - Tension of the bezier curve between points
    bezierCurveTension : 0.4,

    //Boolean - Whether to show a dot for each point
    pointDot : true,

    //Number - Radius of each point dot in pixels
    pointDotRadius : 4,

    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth : 1,

    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,

    //Boolean - Whether to show a stroke for datasets
    datasetStroke : true,

    //Number - Pixel width of dataset stroke
    datasetStrokeWidth : 2,

    //Boolean - Whether to fill the dataset with a colour
    datasetFill : true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

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

  var ctx1 = $("#chart1").get(0).getContext("2d");
  var chart1 = new Chart(ctx1).Line(data1, options);

  var ctx2 = $("#chart2").get(0).getContext("2d");
  var chart2 = new Chart(ctx2).Line(data2, options);
</script>