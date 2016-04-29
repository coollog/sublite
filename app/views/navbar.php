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

  options.right {
    float: right;
  }
  options.left {
    float: left;
  }
  options {
    font-size: 1.5em;
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
    margin-top: 10px;
  }
  opt:hover {
    color: #ffd800;
  }

  .highlight {
    color: #ffd800;
  }
</style>
<?php
  $curdir = dirname($_SERVER['REQUEST_URI'] . '/.');
  // Various states the user can be in
  $states = array(
    "loggedin" => View::get('Loggedin') or View::get('Loggedinstudent'),
    "notloggedin" => !View::get('Loggedin') and !View::get('Loggedinstudent'),
    "notloggedin !/employers" => !View::get('Loggedin') and !View::get('Loggedinstudent') and !preg_match('/^\/employers/', $curdir),
    "notloggedin /employers" => !View::get('Loggedin') and !View::get('Loggedinstudent') and preg_match('/^\/employers/', $curdir),
    "loggedin /employers" => View::get('Loggedin') and !View::get('Loggedinstudent') and preg_match('/^\/employers/', $curdir),
    "recruiter hascompany" => View::get('Loggedin') and View::get('Lcompany'),
    "recruiter nocompany" => View::get('Loggedin') and !View::get('Lcompany'),
    "recruiter" => View::get('Loggedin'),
    "student" => View::get('Loggedinstudent'),
    "student /housing" => View::get('Loggedinstudent') and preg_match('/^\/housing/', $curdir),
    "all" => true
  );
  // Establish relative paths
  $path = $GLOBALS['dirpre'] . '../';
  // Build the menu items and associate them with a state
  $menu = array(
    array("Blog", "https://sublite.wordpress.com/", "all"),

    array("List Job", $path."employers/addjob", "recruiter hascompany"),
    array("Dashboard", $path."employers/home", "recruiter hascompany"),
    array("Messages", $path."employers/messages", "recruiter hascompany"),
    array("Add Company Profile", $path."employers/addcompany", "recruiter nocompany"),
    // array("Edit Company Profile", $path."employers/editcompany", "recruiter hascompany"),

    array("Housing", $path."housing/search", "student"),
    array("Jobs", $path."jobs/search", "student"),
    // array("Socialize", $path."hubs/start", "student"),
    array("Add Sublet", $path."housing/addsublet", "student /housing"),
    array("Dashboard", $path."home", "student"),
    array("Messages", $path."messages", "student"),

    array("Search Housing", $path."housing/search", "notloggedin !/employers"),
    array("Search Jobs", $path."jobs/search", "notloggedin !/employers"),
    array("Summer Social", $path."hubs/start", "notloggedin !/employers"),
    array("List Sublet", $path."register", "notloggedin !/employers"),
    array("List Job", $path."employers/register", "notloggedin /employers"),
    array("Sign Up", $path."register", "notloggedin !/employers"),
    array("Sign Up", $path."employers/register", "notloggedin /employers"),
    array("Log In", $path."employers/login", "notloggedin /employers"),
    array("Log In", $path."login", "notloggedin !/employers"),
    array("Log Out", $path."logout", "student"),
    array("Log Out", $path."employers/logout", "recruiter")
  );
?>
<navbar class="blackbar">
  <a href="/"><logo>SubLite</logo></a>
  <options class="right">
    <?php
      foreach ($menu as $opt) {
        $text = $opt[0];
        $link = $opt[1];
        if (!$states[$opt[2]]) continue;
        if ($opt[0] == "Messages") {
          $num = MessageModel::getNumUnread($_SESSION['_id']);
          if ($num > 0) {
            echo "<a href=\"$link\"><opt>$text " . 
                 "<span class=\"highlight\">($num)</span></opt></a>";
          } else {
            echo "<a href=\"$link\"><opt>$text (0)</span></opt></a>";
          }
        } else if ($opt[0] == "Dashboard") {
          $num = ApplicationModel::getRecruiterTotalUnclaimed($_SESSION['_id']);
          if ($num > 0) {
            echo "<a href=\"$link\"><opt>$text " .
                 "<span class=\"highlight\">($num New Applicants)</span></opt></a>";
          } else {
            echo "<a href=\"$link\"><opt>$text</span></opt></a>";
          }
        } else {
          echo "<a href=\"$link\"><opt>$text</opt></a>";
        }
      }
    ?>
  </options>
</navbar>