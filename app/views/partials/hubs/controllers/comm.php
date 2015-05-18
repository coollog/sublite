<script>
  // Interface code for communication
  var Comm = {
    apiuri: '<?php echo $GLOBALS['dirpre']; ?>../hubs/api.php',
    parse: function (content) {
      // Parse based on the following format (2 lines):
      // MESSAGENAME
      // JSONDATA

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
      // Send message via post

      var json = {
        name: name,
        data: data
      };
      $.post(apiuri, json, function (data) {
        data = this.parse(data);
        callback(data);
      });
    },
    retrieve: function (type, id, callback) {
      // Retrieve a doc

      switch (type) {
        case 'hub':
          this.emit('load hub info', callback);
          break;
        case 'meetup':
          break;
      }
    },
    afterRender: function () {
      // This is where all code to setup interactive communication goes


    }
  };
</script>