<style>
  .blackbar {
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
    text-transform: uppercase;
    display: block;
    background: #000;
    box-sizing: border-box;
    line-height: 1.5em;
  }
  navbar {
    border-bottom: 3px solid #ffd800;
    min-height: 50px;
    padding: 15px 20px 5px 20px;
  }
  navbar:after{
    clear: both;
    content: "";
    display: block;
  }
  navbar logo {
    font-size: 2.5em;
    color: #ffd800;
    line-height: 30px;
    height: 30px;
  }
  navbar beta {
    font-size: 1em;
    color: #ADCC14;
    vertical-align: bottom;
    margin-left: 0.5em;
  }
  options.right {
    float: right;
  }
  options.left {
    float: left;
  }
  options {
    font-size: 1.5em;
    padding-top: 10px;
    margin-bottom: -5px;
    min-height: 30px;
    box-sizing: border-box;
  }
  opt {
    margin-left: 40px;
    cursor: pointer;
    transition: all 0.1s ease-in-out;
    display: inline-block;
    color: #fff;
  }
  opt:hover {
    color: #ffd800;
  }
  opt.small {
    font-size: 0.8em;
  }
</style>

<navbar class="blackbar">
  <a href="."><logo>SubLite</logo></a><beta>beta</beta>
  <options class="right">
    <a href="."><opt class="small">Home Page</opt></a>
    
    <?php if (vget('Loggedin')) { ?>

      <?php if (vget('Lcompany')) { ?>
        <a href="addjob.php"><opt>List Job</opt></a>
        <a href="home.php"><opt>Manage</opt></a>
        <a href="messages.php"><opt>Messages</opt></a>
      <?php } else { ?>
        <a href="addcompany.php"><opt>Add Company Profile</opt></a>
      <?php } ?>
      <a href="logout.php"><opt>Log Out</opt></a>

    <?php } elseif (vget('Loggedinstudent')) { ?>

      <a href="<?php echo $GLOBALS['dirpre']; ?>../housing"><opt>Search For Housing</opt></a>
      <a href="<?php echo $GLOBALS['dirpre']; ?>../jobs/search.php"><opt>Search For Jobs</opt></a>
      <a href="messages.php"><opt>Messages</opt></a>
      <a href="logout.php"><opt>Log Out</opt></a>

    <?php } else { ?>
      <a href="login.php"><opt>Log In</opt></a>
    <?php } ?>
  </options>
</navbar>