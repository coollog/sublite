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
  .error {
    display: none;
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
    background-attachment: fixed;
  }
    .banner name {
      font-size: 2em;
      color: #ffd800;
      position: absolute;
      display: block;
      bottom: 0;
      text-shadow: 0px 0px 4px #000;
    }
  #myhubpanel {
    display: none;
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
    width: 500px;
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
    height: 2em;
    font-size: 0.8em;
  }
  .posts likes:hover {
    opacity: 0.5;
    cursor: pointer;
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
  .posts likes.liked:before {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/heartred.png');
  }
  .posts replies:hover {
    opacity: 0.5;
    cursor: pointer;
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
  .reply {
    margin-top: 1em;
  }
  .reply form {
    margin: 0;
  }
  .reply textarea {
    height: 3em;
    white-space: pre-wrap;
    transition: 0.2s all ease-in-out;
  }
  .reply button {
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
  .meetup .tabframe[name=editmeetup] {
    display: none;
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

  tabtemplates, viewtemplates {
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
  meetupview #leavemeetupdiv {
    display: none;
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
  <?php vpartial('hubs/viewtemplates/hub'); ?>
  <?php vpartial('hubs/viewtemplates/meetup'); ?>
</viewtemplates>
<tabtemplates>
  <?php vpartial('hubs/tabtemplates/post'); ?>
  <?php vpartial('hubs/tabtemplates/meetup'); ?>
  <?php vpartial('hubs/tabtemplates/members'); ?>
</tabtemplates>

<?php vpartial('hubs/controllers/views'); ?>
<?php vpartial('hubs/controllers/tabs'); ?>

<?php vpartial('hubs/controllers/afterrender'); ?>
<?php vpartial('hubs/controllers/comm'); ?>

<script>
  // Actual code to set everything up for the first time

  // Config
  var myid = null,
      thishub = '<?php vecho('hub'); ?>',
      thishubname = '';

  Views.setup();

  Posts.setup();
  Meetups.setup();
  Members.setup();

  // Get current hub
  Comm.retrieve('hub', thishub, function () {});
</script>