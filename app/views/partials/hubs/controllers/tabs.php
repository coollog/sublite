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
        $('.postsframe[type='+type+'] .thread[for='+parentid+'] .replies').append(newHTML);
    },
    clear: function (type) {
      Tabs.clear('.postsframe[type='+type+'] .posts');
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
    }
  }
</script>