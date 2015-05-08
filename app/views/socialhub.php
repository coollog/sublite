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
  .posts pic {
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
    /*display: none;*/
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
      Members
    </tab>
  </content>
</panel>

<panel class="tabframe" name="forum">
  <content>
    <subtabs>
      <subtab class="focus">Most Recent</subtab> | <subtab>Most Popular</subtab>
    </subtabs>
    <div class="postsframe">
      <div class="posts">
        <table class="post" index="1"><tr>
          <td class="l"><pic style="background-image: url('../app/assets/gfx/why1.jpg');"></pic></td>
          <td class="r">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            <info>By Annie C. from Yale in NYC, 3 hrs ago</info>
            <likes>9</likes><replies>9</replies>
          </td>
        </tr></table>
        <div class="replies" for="1">
          <table class="post" index="2"><tr>
            <td class="l"><pic style="background-image: url('../app/assets/gfx/why1.jpg');"></pic></td>
            <td class="r">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              <info>By Annie C. from Yale in NYC, 3 hrs ago</info>
              <likes>9</likes><replies>9</replies>
            </td>
          </tr></table>
        </div>
      </div>
    </div>
  </content>
</panel>
<panel class="tabframe" name="meetups">
  <content>
    
  </content>
</panel>
<panel class="tabframe" name="members">
  <content>

  </content>
</panel>

<templates>
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
</templates>
<script>
  var Meetup = {
    setup: function () {
      // Read in template
      this.template = $('template[for=meetup]').html();
    },
    addMeetup: function (json) {
      var newHTML = this.template;
      for (var key in json) {
        newHTML = newHTML.replace('{'+key+'}', json[key]);
      }
      $('.tabframe[name=meetups] content').append(newHTML);
      console.log(newHTML);
    },
    clearMeetups: function () {
      $('.meetups .content').html('');
    }
  }
  Meetup.setup();
  Meetup.addMeetup({
    name: 'Cherry Blossom Festival and Parade',
    datetime: 'Sunday Apr 19, 9:00 AM - Friday May 1, 6:00 PM',
    place: 'Union Bank<br />1675 Post Street, San Francisco, CA',
    going: 23,
    comments: 5
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

  var postsleft = 0;
  $('.post').click(function() {
    var myindex = $(this).attr('index'),
        replies = '.replies[for='+myindex+']';
    if ($(replies).length) {
      var mytab = $(this).parent().css('marginLeft');
      postsleft = -parseInt(mytab);
      if (!$(replies).is(":visible")) {
        postsleft = -50 - parseInt(mytab);
      }
      var op = this;
      $(replies).slideToggle('100', 'easeInOutCubic', function() {
        scrollTo(op);
      });
      $('.posts').css('left', postsleft+'px');
    }

  });
</script>