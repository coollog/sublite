<style>
  * {
    box-sizing: border-box;
  }
  panel {
    padding: 0;
  }
  panel, navbar, footer {
    min-width: 600px;
  }
  content {
    padding: 40px;
    position: relative;
    display: block;
    margin: 0 auto;
    height: 100%;
    max-width: 800px;
  }
  .nopadding {
    padding: 0;
  }
  button {
    padding: 0 40px;
    color: #fff;
    background: #c94747;
    box-sizing: border-box;
    border: 0;
    transition: all 0.10s ease-in-out;
    cursor: pointer;
    font-size: 2em;
    line-height: 1.5em;
    letter-spacing: 1px;
    outline: none;
  }
  button:hover {
    opacity: 0.5;
  }
  button.smallbutton {
    font-size: 1em;
    line-height: 1.5em;
    letter-spacing: 0;
    padding: 0 20px;
  }
  .banner {
    height: 33vh;
    min-height: 200px;
    padding: 40px;
    background: #ffe no-repeat center center;
    background-size: cover;
  }
    .banner name {
      font-size: 2em;
      color: #ffd800;
      position: absolute;
      display: block;
      bottom: 0;
    }
  .tabs {
    background: #f3f3f2;
    font-size: 1.4em;
    text-align: left;
  }
    tab {
      color: #b5a8a8;
      padding: 1em 1.5em;
      cursor: pointer;
      height: 100%;
      display: inline-block;
      box-sizing: border-box;
    }
    tab.focus, tab:hover {
      background: #faf9f9;
      color: #000;
    }
  .tabframe {
    background: #f3f3f2;
    display: none;
  }
  .tabframe content {
    background: #faf9f9;
    min-height: 50vh;
    position: relative;
    height: 100%;
    display: block;
  }
    subtabs {
      display: block;
      color: #b5a8a8;
      text-align: left;
      margin-bottom: 1em;
    }
    subtab {
      cursor: pointer;
    }
    subtab.focus, subtab:hover {
      color: #000;
    }

  .postsframe {
    position: relative;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
  }
  .postsframe[type=popular] {
    display: none;
  }
  .posts {
    height: 100%;
    overflow: visible;
    position: relative;
    transition: 0.1s all ease-in-out;
  }
  .posts .post {
    text-align: left;
    line-height: 1.5em;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    width: 500px;
  }
  .posts .post:hover {
    opacity: 0.8;
  }
  .posts .l {
    min-width: 100px;
    position: relative;
    width: 15%;
    vertical-align: top;
  }
  .posts pic, .member pic {
    width: 80px;
    height: 80px;
    border-radius: 40px;
    display: block;
    background: transparent no-repeat center center;
    background-size: cover;
  }
  .posts info {
    display: block;
    height: 1.5em;
  }
  .posts likes:before {
    content: "";
    background: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/heart.png') no-repeat center center;
    background-size: contain;
    display: inline-block;
    width: 1.5em;
    height: 1em;
    margin: .25em .5em .25em 0;
  }
  .posts replies:before {
    content: "";
    background: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/comments.png') no-repeat center center;
    background-size: contain;
    display: inline-block;
    width: 1.5em;
    height: 1em;
    margin: .25em .5em;
  }
  .posts .replies {
    margin-left: 50px;
    display: none;
  }

  .meetup {
    border-bottom: 1px solid #ddd;
    text-align: left;
    padding: 10px 0;
  }
  .meetup name {
    font-size: 2em;
    line-height: 1.5em;
  }
  .meetup .info {
    margin: 20px 0;
  }
  .meetup .l {
    background: transparent no-repeat center center;
    background-size: auto 80%;
    width: 20%;
    min-width: 100px;
    height: 50px;
  }
  .meetup datetime {
    display: block;
  }
  .meetup place {
    font-size: 0.9em;
    display: block;
  }
  .meetup info {
    float: right;
    display: inline-block;
  }

  .member {
    margin: 10px 40px;
    text-align: left;
  }
  .member .l {
    width: 15%;
    min-width: 120px;
    text-align: center;
  }
  .member name {
    font-size: 1.4em;
    display: block;
  }
  .member info {
    color: #888;
    display: block;
  }

  templates {
    display: none;
  }
</style>

<panel class="banner">
  <content>
    <name>New York City Area Hub</name>
  </content>
</panel>

<panel>
  <content>
    <button class="joinhub">Join Hub</button>
  </content>
</panel>

<panel class="tabs">
  <content class="nopadding">
    <tab for="forum" class="focus">
      Forum
    </tab><tab for="meetups">
      Meet-Ups
    </tab><tab for="members">
      Members (<membercount></membercount>)
    </tab>
  </content>
</panel>

<panel class="tabframe" name="forum">
  <content>
    <subtabs>
      <subtab type="recent" class="focus">Most Recent</subtab> | <subtab type="popular">Most Popular</subtab>
    </subtabs>
    <div class="postsframe" type="recent"><div class="posts"></div></div>
    <div class="postsframe" type="popular"><div class="posts"></div></div>
  </content>
</panel>
<panel class="tabframe" name="meetups">
  <content></content>
</panel>
<panel class="tabframe" name="members">
  <content>
    <subtabs><membercount></membercount> Members</subtabs>
    <div class="members"></div>
  </content>
</panel>

<templates>
  <template for="post">
    <table class="post" index="{id}"><tr>
      <td class="l"><pic style="background-image: url('{pic}');"></pic></td>
      <td class="r">
        {text}
        <info>By {name} from {hub}, {time}</info>
        <likes>{likes}</likes><replies>{replies}</replies>
      </td>
    </tr></table>
    <div class="replies" for="{id}"></div>
  </template>
  <template for="meetup">
    <div class="meetup">
      <name>{name}</name>
      <table class="info">
        <tr>
          <td class="l" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/calendar.png');"></td>
          <td>
            <datetime>{datetime}</datetime>
          </td>
        </tr>
        <tr>
          <td class="l" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/place.png');"></td>
          <td>
            <place>{place}</place>
          </td>
        </tr>
      </table>
      <button class="smallbutton">RSVP</button>
      <info>{going} Going &nbsp; &nbsp; {comments} Comments</info>
    </div>
  </template>
  <template for="members">
    <table class="member"><tr>
      <td class="l">
        <pic style="background-image: url('{pic}');"></pic>
      </td>
      <td>
        <name>{name}</name>
        <info>
          {school}<br />
          {joined}
        </info>
      </td>
    </tr></table>
  </template>
</templates>
<script>
  var Posts = {
    setup: function () {
      // Read in template
      this.template = $('template[for=post]').html();
    },
    add: function (type, json, parentid) {
      var newHTML = this.template;
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }
      if (typeof parentid == 'undefined')
        $('.postsframe[type='+type+'] .posts').append(newHTML);
      else
        $('.postsframe[type='+type+'] .replies[for='+parentid+']').append(newHTML);
    },
    clear: function (type) {
      $('.postsframe[type='+type+'] .posts').html('');
    }
  }
  var Meetups = {
    setup: function () {
      // Read in template
      this.template = $('template[for=meetup]').html();
    },
    add: function (json) {
      var newHTML = this.template;
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }
      $('.tabframe[name=meetups] content').append(newHTML);
    },
    clear: function () {
      $('.tabframe[name=meetups] content').html('');
    }
  }
  var Members = {
    setup: function () {
      // Read in template
      this.template = $('template[for=members]').html();
      this.updateCount(0);
    },
    add: function (json) {
      var newHTML = this.template;
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }
      $('.members').append(newHTML);
      this.updateCount(this.membercount + 1);
    },
    clear: function () {
      $('.members').html('');
      this.updateCount(0);
    },
    updateCount: function (count) {
      this.membercount = count;
      $('membercount').html(this.membercount);
    }
  }

  Posts.setup();
  Meetups.setup();
  Members.setup();

  Posts.add('recent', {
    id: 1,
    pic: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why1.jpg',
    text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    name: 'Annie C.',
    hub: 'Yale in NYC',
    time: '3 hrs ago',
    likes: 9,
    replies: 20
  });
  Posts.add('recent', {
    id: 2,
    pic: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why1.jpg',
    text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    name: 'Annie C.',
    hub: 'Yale in NYC',
    time: '3 hrs ago',
    likes: 9,
    replies: 20
  }, 1);
  Posts.add('popular', {
    id: 1,
    pic: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why1.jpg',
    text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    name: 'Annie C.',
    hub: 'Yale in NYC',
    time: '3 hrs ago',
    likes: 9,
    replies: 20
  });
  Meetups.add({
    name: 'Cherry Blossom Festival and Parade',
    datetime: 'Sunday Apr 19, 9:00 AM - Friday May 1, 6:00 PM',
    place: 'Union Bank<br />1675 Post Street, San Francisco, CA',
    going: 23,
    comments: 5
  });
  Members.add({
    name: 'Random Person',
    pic: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why2.jpg',
    school: 'Yale University',
    joined: 'Member, 5/17/2015'
  });
</script>

<script>
  // Tabulation

  function getTabframe(tab) {
    var name = $(tab).attr('for');
    return '.tabframe[name='+name+']';
  }
  function closeTab(tab) {
    $(tab).removeClass('focus');
    $(getTabframe(tab)).hide();
  }
  function openTab(tab) {
    $(tab).addClass('focus');
    $(getTabframe(tab)).show();
  }

  $('tab').click(function() {
    var me = this;
    $('tab').each(function() {
      if (this != me) {
        closeTab(this);
      }
    });
    openTab(me);
  });
  $('tab').each(function() {
    if ($(this).hasClass('focus'))
      openTab(this);
  });

  // Posts tabbing

  $('.post').click(function() {
    var postsleft,
        myindex = $(this).attr('index'),
        replies = $(this).parent().children('.replies[for='+myindex+']');

    if (replies.length && replies.html().length) {
      var mytab = $(this).parent().css('marginLeft');
      postsleft = -parseInt(mytab);
      if (!replies.is(":visible")) {
        postsleft = -50 - parseInt(mytab);
      }
      var op = this;
      replies.slideToggle('100', 'easeInOutCubic', function() {
        scrollTo(op);
      });
      var posts = $(this).parent();
      while (!posts.hasClass('posts'))
        posts = posts.parent();
      posts.css('left', postsleft+'px');
    }

  });
  $('.tabframe subtab').click(function() {
    if (!$(this).hasClass('focus')) {
      var type = $(this).attr('type');
      $('.postsframe').hide();
      $('.postsframe[type='+type+']').show();
      $('.tabframe subtab').removeClass('focus');
      $(this).addClass('focus');
    }
  });
</script>