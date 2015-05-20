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
          this.emit('load hub info', { hub: id }, callback);
          break;
        case 'meetup':
          this.emit('load event info', { event: id }, callback);
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

        var emitdata = {
          content: content,
          parentid: parentid
        };
        if ($('meetupview').length > 0)
          emitdata.event = $('meetupview').attr('for');

        Comm.emit('new post', emitdata, function (err, data) {
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
            id: data.id,
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

      // Meetup view switching

      $('.meetup button').off("click").click(function () {
        var eventid = $(this).parent().attr('for');

        Comm.retrieve('meetup', eventid, function (err, data) {
          if (err) { alert(err); return; }

          Views.render('meetup', {
            banner: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why3.jpg',
            name: data.title,
            hub: thishubname,
            datetime: data.starttime + ' - ' + data.endtime,
            place: data.location + '<br />' + data.address,
            host: data.hostname,
            hostpic: data.hostphoto,
            description: data.description,
            id: eventid,
            iscreator: data.iscreator,
            isgoing: data.isgoing
          }, false, function () {
            // Load comments
            Comm.emit('load event comments', { event: eventid }, function (err, data) {
              if (err) { alert(err); return; }

              Posts.load(data);

              afterRender();
              console.log('comments: ', data);
            });
            // Load going
            Comm.emit('list going', { event: eventid }, function (err, data) {
              if (err) { alert(err); return; }

              data.forEach(function (student) {
                Members.add({
                  name: student.name,
                  pic: student.pic,
                  school: student.school,
                  joined: student.joined
                });
              });

              afterRender();
              console.log('going: ', data);
            });
          });
        });
      });
      $('meetupview .details hub').off("click").click(function () {
        Views.render('hub', {}, true, function () {
          // addTestContent(); // remove this
        });
      });
    }
  };
</script>