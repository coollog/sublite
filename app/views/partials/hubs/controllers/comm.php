<script>
  // Interface code for communication
  var Comm = {
    apiuri: '<?php echo $GLOBALS['dirpre']; ?>../hubs/api.php',
    setup: function (id, pass) {

    },
    parse: function (json) {
      // Parse based on the following format (2 lines):
      // MESSAGENAME
      // JSONDATA

      json = JSON.parse(json);
      var status = json.status,
          data = json.data,
          message = json.message;
      return {
        status: status,
        data: data,
        message: message
      };
    },
    emit: function (name, data, callback) {
      // Send message via post

      var json = {
        name: name,
        json: data
      };
      var c = this;
      $.post(this.apiuri, json, function (data) {
        console.log('received data:', data);
        data = c.parse(data);
        callback(data);
      });
    },
    retrieve: function (type, id, callback) {
      // Retrieve a doc

      switch (type) {
        case 'hub':
          this.emit('load hub info', {
            hub: id
          }, callback);
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