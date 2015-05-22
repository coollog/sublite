<script>
  // Controllers for the tab templates

  // Parent class
  var Tabs = {
    setup: function (me, type) {
      // Read in template
      me.template = $('tabtemplate[for='+type+']').html();
      $('tabtemplate[for='+type+']').remove();
    },
    newHTML: function (me, json) {
      var newHTML = me.template;
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }
      return newHTML;
    },
    clear: function (elem) {
      $(elem).html('');
    }
  }

  var Posts = {
    setup: function () {
      Tabs.setup(this, 'post');
    },
    add: function (type, json, parentid) {
      var newHTML = Tabs.newHTML(this, json);
      if (typeof parentid == 'undefined' || parentid == '')
        $('.postsframe[type='+type+'] .posts').append(newHTML);
      else
        $('.postsframe[type='+type+'] .thread[for='+parentid+']').children('.replies').append(newHTML);

      // Highlight if liked
      if (json.liked)
        $('.post[index='+json.id+']').find('likes').addClass('liked');
    },
    clear: function (type) {
      Tabs.clear('.postsframe[type='+type+'] .posts');
    },
    load: function (posts) {
      posts.forEach(function (post) {
        console.log('child: ', post);

        Posts.add('recent', {
          id: post.id,
          pic: post.pic,
          text: post.content,
          name: post.name,
          hub: thishubname,
          time: post.date,
          likes: post.likes.length,
          replies: post.children.length,
          liked: post.liked
        }, post.parent);
        if (post.children.length > 0) {
          Posts.load(post.children);
        }
      });
    }
  }
  var Meetups = {
    setup: function () {
      Tabs.setup(this, 'meetup');
    },
    add: function (json) {
      var newHTML = Tabs.newHTML(this, json);
      $('.meetups').append(newHTML);
    },
    clear: function () {
      Tabs.clear('.meetups');
    }
  }
  var Members = {
    setup: function () {
      Tabs.setup(this, 'members');
    },
    add: function (json) {
      var newHTML = Tabs.newHTML(this, json);
      $('.members').append(newHTML);
      this.updateCount();
    },
    clear: function () {
      Tabs.clear('.members');
      this.updateCount();
    },
    updateCount: function () {
      var n = $('.member').length;
      $('membercount').html(n);
    },
    load: function (type, id) {
      switch (type) {
        case 'hub':
          Comm.emit('load members tab', {}, function (err, data) {
            if (err) { alert(err); return; }

            Members.clear();
            data.forEach(function (student) {
              Members.add({
                id: student.id,
                name: student.name,
                pic: student.pic,
                school: student.school,
                joined: student.joined
              });
            });

            afterRender();
            console.log('members: ', data);
          });
          break;

        case 'meetup':
          Comm.emit('list going', { event: id }, function (err, data) {
            if (err) { alert(err); return; }

            Members.clear();
            data.forEach(function (student) {
              Members.add({
                id: student.id,
                name: student.name,
                pic: student.pic,
                school: student.school,
                joined: student.joined
              });
            });

            afterRender();
            console.log('going: ', data);
          });
          break;
      }
    }
  }
</script>