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

      data['hub'] = thishub;
      var json = {
        name: name,
        json: data
      };
      var c = this;
      $.post(this.apiuri, json, function (data) {
        console.log('received data:', data);
        data = c.parse(data);

        var err = null;
        if (data.status == 'fail') err = data.message;
        data = data.data;

        callback(err, data);
      });
    },
    retrieve: function (type, id, callback) {
      // Retrieve a doc

      switch (type) {
        case 'hub':
          this.emit('load hub info', {}, callback);
          break;
        case 'meetup':
          break;
      }
    },
    afterRender: function () {
      // This is where all code to setup interactive communication goes

      // Hubs stuff

      $('.joinhub').click(function () {
        Comm.emit('join hub', {}, function (err, data) {
          if (err) { alert(err); return; }

          $('#joinpanel').remove();
        });
      });

      // Posts

      $('.reply form').submit(function() {
        var json = formJSON(this),
            content = json.text,
            parentid = $(this).parents('.thread').attr('for');
        console.log(parentid);

        Comm.emit('new post', {
          content: content,
          parentid: parentid
        }, function (err, data) {
          if (err) { alert(err); return; }

          Posts.add('recent', {
            id: data.id,
            pic: data.pic,
            text: data.content,
            name: data.name,
            hub: thishubname,
            time: data.date,
            likes: data.likes.length,
            replies: data.children.length
          }, data.parent);

          afterRender();
        });

        $(this).find('textarea').val('');
        $('html').click();

        return false;
      });

      // Meetups

      $('.tabframe[name=createmeetup] form').submit(function() {
        var json = formJSON(this);
        console.log('creating event: ', json);

        var form = this;

        Comm.emit('create event', {
          eventtitle: json.title,
          starttime: json.startdate + ' ' + json.starttime,
          endtime: json.enddate + ' ' + json.endtime,
          locationname: json.locationname,
          address: json.address,
          description: json.description
        }, function (err, data) {
          if (err) { $(form).children('notice').html(err); return; }

          Meetups.add({
            name: data.title,
            datetime: data.starttime + ' - ' + data.endtime,
            // 'Sunday Apr 19, 9:00 AM - Friday May 1, 6:00 PM'
            place: data.location + '<br />' + data.address,
            // 'Union Bank<br />1675 Post Street, San Francisco, CA',
            going: data.going.length,
            comments: data.comments.length
          });

          afterRender();

          $('tab[for=meetups]').click();
        });

        return false;
      });
    }
  };
</script>