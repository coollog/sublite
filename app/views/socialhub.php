<style>
  * {
    box-sizing: border-box;
  }
  panel {
    padding: 0;
    background: #fff;
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
  headline {
    margin: 0;
    line-height: 1.5em;
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
    font-size: 1.5em;
    line-height: 2em;
    letter-spacing: 1px;
    outline: none;
  }
  button.joinhub {
    font-size: 2em;
    line-height: 1.5em;
    padding: 0 100px;
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
  button.gray {
    background: #b4a8a8;
  }
  button.half {
    width: 46%;
    margin-right: 2%;
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
    text-align: left;
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
  pic {
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
  .posts .thread {
    margin-left: 50px;
    display: none;
  }
  .posts .reply {
    margin-top: 1em;
  }
  .posts .reply form {
    margin: 0;
  }
  .posts .reply textarea {
    height: 3em;
    white-space: pre-wrap;
    transition: 0.2s all ease-in-out;
  }
  .posts .reply button {
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
  .meetup .info, meetupview .details .info {
    margin: 20px 0;
  }
  .meetup .l, meetupview .details .l {
    background: transparent no-repeat center center;
    background-size: auto 80%;
    width: 20%;
    min-width: 100px;
    height: 50px;
  }
  .meetup datetime, meetupview .details datetime {
    display: block;
  }
  .meetup place, meetupview .details place {
    font-size: 0.9em;
    display: block;
  }
  .meetup info {
    float: right;
    display: inline-block;
  }

  .createmeetup {

  }
  .createmeetup input {
    display: block;
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

  templates, viewtemplates {
    display: none;
  }

  meetupview .details content {
    text-align: left;
    border-bottom: 1px solid #ddd;
    padding-bottom: 0;
  }
  meetupview .details name {
    font-size: 2em;
    line-height: 1.2em;
    display: block;
  }
  meetupview .details hub {
    display: block;
    cursor: pointer;
  }
  meetupview .details pic {
    width: 60px;
    height: 60px;
    border-radius: 30px;
    margin: 0 auto;
  }
  meetupview .goingornot {
    text-align: left;
  }
  meetupview .goingornot areyou {
    font-size: 1.7em;
    line-height: 1.5em;
    margin-bottom: .5em;
    display: block;
  }
  meetupview .tabframe[name=description] {
    text-align: left;
  }

  viewframe {
    width: 100%;
    display: block;
    overflow: hidden;
    position: relative;
  }
  newview {
    position: relative;
    width: 100%;
  }
  view {
    position: relative;
    width: 100%;
    height: 100%;
    top: 0;
  }
  view, newview {
  }
</style>

<viewframe>
  <view></view>
  <newview></newview>
</viewframe>

<viewtemplates>
  <viewtemplate name="hub">
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
          Members (<membercount>0</membercount>)
        </tab><tab for="createmeetup" style="display: none;">
          Create Meet-Up
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
      <content>
        <button id="createmeetup">Create a Meet-Up</button>
        <br /><br />
        <div class="meetups"></div>
      </content>
    </panel>
    <panel class="tabframe" name="createmeetup">
      <content>
        <headline>Create a Meet-Up</headline>
        <form>
          <notice></notice>
          Title:
          <input type="text" name="title" />
          Start Date:
          <input class="datepicker" type="text" name="startdate" />
          Start Time:
          <input class="timepicker" type="time" name="starttime" />
          End Date:
          <input class="datepicker" type="text" name="enddate" />
          End Time:
          <input class="timepicker" type="time" name="endtime" />
          Location Name:
          <input type="text" name="locationname" />
          Location Address:
          <input type="text" name="locationaddress" />
          <notice></notice>
          <right><button>Create</button></right>
        </form>
      </content>
    </panel>
    <panel class="tabframe" name="members">
      <content>
        <subtabs><membercount>0</membercount> Members</subtabs>
        <div class="members"></div>
      </content>
    </panel>
  </viewtemplate>

  <viewtemplate name="meetup">
    <meetupview>
      <panel class="banner" style="background-image: url('{banner}');"></panel>

      <panel class="details">
        <content>
          <name>{name}</name>
          <hub><a>{hub}</a></hub>
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
            <tr>
              <td class="l"><pic style="background-image: url('{hostpic}');"></pic></td>
              <td>
                Hosted by {host}
              </td>
            </tr>
          </table>
        </content>
      </panel>

      <panel class="goingornot">
        <content>
          <areyou>Are you going?</areyou>
          <button class="half">Yes</button><button class="gray half">No</button>
        </content>
      </panel>

      <panel class="tabs">
        <content class="nopadding">
          <tab for="members" class="focus">
            Going (<membercount>0</membercount>)
          </tab><tab for="description">
            Description
          </tab><tab for="forum">
            Comments
          </tab>
        </content>
      </panel>

      <panel class="tabframe" name="members">
        <content>
          <subtabs><membercount>0</membercount> Going</subtabs>
          <div class="members"></div>
        </content>
      </panel>
      <panel class="tabframe" name="description">
        <content>{description}</content>
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
    </viewtemplate>
    </meetupview>
  </viewtemplate>
</viewtemplates>
<script>
  // Controller for the views templates

  var Views = {
    templates: {},
    setup: function () {
      var templates = this.templates;
      $('viewtemplate').each(function() {
        var name = $(this).attr('name');
        templates[name] = $(this).html();
        $(this).remove();
      });
    },
    // Changes the view with json to replace {var} and back=true meaning
    //  slide view from left instead
    render: function (name, json, back) {
      var newHTML = this.templates[name];
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }

      var oldHTML = $('view').html();
      if (oldHTML.length) {
        var viewPos = {
          viewStart: '0%',
          viewEnd: '-100%',
          newviewStart: '100%',
          newviewEnd: '0%'
        };
        if (typeof back !== 'undefined') {
          viewPos = {
            viewstart: '0%',
            viewEnd: '100%',
            newviewStart: '-100%',
            newviewEnd: '0%'
          };
        }
        $('view').css('position', 'absolute')
                 .css('left', viewPos.viewStart)
                 .animate({ left: viewPos.viewEnd },
                 500, 'easeOutCubic');
        $('newview').css('left', viewPos.newviewStart).html(newHTML)
                    .animate({ left: viewPos.newviewEnd }, 
                    500, 'easeOutCubic', function() {
          $('view').html(newHTML).css('position', 'relative').css('left', '0');
          $('newview').html('');
          afterRender();
          addTestContent(); // remove this
        });
      } else {
        $('view').html(newHTML);
        addTestContent(); // remove this
      }
    }
  };
</script>

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
    <div class="thread" for="{id}">
      <div class="replies"></div>
      <div class="reply">
        Write your comment:
        <form>
          <textarea name="text"></textarea>
          <right><button>Reply</button></right>
        </form>
      </div>
    </div>
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
      <button class="smallbutton">More</button>
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
  // Controllers for the tab templates

  var Posts = {
    setup: function () {
      // Read in template
      this.template = $('template[for=post]').html();
      $('template[for=post]').remove();
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
        $('.postsframe[type='+type+'] .thread[for='+parentid+'] .replies').append(newHTML);
      afterRender();
    },
    clear: function (type) {
      $('.postsframe[type='+type+'] .posts').html('');
    }
  }
  var Meetups = {
    setup: function () {
      // Read in template
      this.template = $('template[for=meetup]').html();
      $('template[for=meetup]').remove();
    },
    add: function (json) {
      var newHTML = this.template;
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }
      $('.meetups').append(newHTML);
      afterRender();
    },
    clear: function () {
      $('.meetups').html('');
    }
  }
  var Members = {
    setup: function () {
      // Read in template
      this.template = $('template[for=members]').html();
      $('template[for=members]').remove();
      this.updateCount();
    },
    add: function (json) {
      var newHTML = this.template;
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }
      $('.members').append(newHTML);
      this.updateCount();
      afterRender();
    },
    clear: function () {
      $('.members').html('');
      this.updateCount();
    },
    updateCount: function () {
      var n = $('.member').length;
      $('membercount').html(n);
    }
  }
</script>

<script>
  // Function to holistically prepare new renders

  function afterRender() {
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

    $('tab').off("click").click(function() {
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

    $('.post').off("click").click(function() {
      var postsleft,
          myindex = $(this).attr('index'),
          replies = $(this).parent().children('.thread[for='+myindex+']');

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
    $('.tabframe[name=forum] subtab').off("click").click(function() {
      if (!$(this).hasClass('focus')) {
        var type = $(this).attr('type');
        $('.postsframe').hide();
        $('.postsframe[type='+type+']').show();
        $('.tabframe[name=forum] subtab').removeClass('focus');
        $(this).addClass('focus');
      }
    });

    // Posts replying

    $('.posts .reply textarea').off('blur').off('focus')
      .focus(function() {
        $(this).css('height', '10em')
          .parent().find('button').slideDown(200, 'easeOutCubic');
      })
      .blur(function() {
        $(this).css('height', '3em')
          .parent().find('button').slideUp(200, 'easeOutCubic');
      });

    // Meetup view switching
    $('.meetup button').off("click").click(function () {
      Views.render('meetup', {
        banner: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why3.jpg',
        name: "Let's Go Party in New York!",
        hub: 'Yale University in New York City',
        datetime: 'Tuesday Aug 15, 9:00 PM - 11:00 PM',
        place: 'General Assembly<br />1933 S. Broadway, 11th Floor, Los Angeles, 900007, CA',
        host: 'Name of Person',
        hostpic: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why3.jpg',
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
      });
    });
    $('meetupview .details hub').off("click").click(function () {
      Views.render('hub', {}, true);
    });

    // Meetup creation button
    $('#createmeetup').off("click").click(function () {
      $('tab[for=createmeetup]').click();
    });

    // Form submission
    $('form').off('submit').submit(function () {
      console.log(formJSON(this));
      return false;
    });
  }

  function addTestContent() {
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
  }

  // Actual code to set everything up for the first time

  Views.setup();

  Posts.setup();
  Meetups.setup();
  Members.setup();

  Views.render('hub');
</script>