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
    padding: 10px 20px;
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
    font-size: 0.5em;
  }
</style>

<navbar class="blackbar">
  <a href="."><logo>SubLite</logo></a>
  <options class="right">
    <a href="."><opt class="small">Employers' Page</opt></a>
    <?php if (vget('Loggedin')) { ?>
      <a href="addjob.php"><opt>List Job</opt></a>
      <a href="home.php"><opt>Manage</opt></a>
      <a href="logout.php"><opt>Log Out</opt></a>
    <?php } else { ?>
      <a href="loginregister.php"><opt>Register/Log In</opt></a>
    <?php } ?>
  </options>
</navbar>