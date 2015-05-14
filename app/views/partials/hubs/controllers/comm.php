<script>
  // Interface code for communication
  var Comm = {
    apiuri: 'api.php',
    parse: function (content) {
      var split = content.split('\n'),
          name = split[0],
          json = split[1];

      json = JSON.parse(json);
      var status = json.status,
          data = json.data,
          message = json.message;
      return {
        name: name,
        status: status,
        data: data,
        message: message
      };
    },
    emit: function (name, data, callback) {
      var json = {
        name: name,
        data: data
      };
      $.post(apiuri, json, function (data) {
        data = this.parse(data);
        callback(data);
      });
    }
  };
</script>