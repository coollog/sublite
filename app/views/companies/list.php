<templates>

</templates>

<script>
  function loadContent(id, data, callback) {
    var route = 'ajax/' + id;
    data.jobId = '<?php View::echof('jobId'); ?>';

    $.post(route, data, function (data) {
      console.log("'" + route + "' returned with:");
      console.log(data);
      data = JSON.parse(data);
      callback(data);
    });
  }
</script>

<panel>
  <div class="content">
    <headline>Companies</headline>

    <list></list>
  </div>
</panel>