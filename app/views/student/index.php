<style>
  panel.main {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/studentmain.jpg') no-repeat top center;
    background-size: cover;
    background-position: center 0;
    /*background-attachment: fixed;*/
    display: table;
    height: 90%;
    padding-bottom: 0;
    position: relative;
    box-sizing: border-box;
  }
  .arrow-down {
    width: 0;
    height: 0;
    border-left: 30px solid transparent;
    border-right: 30px solid transparent;
    border-top: 30px solid #ffd800;
    left: 50%;
    margin-left: -30px;
    position: absolute;
    bottom: 30px;
    cursor: pointer;
    opacity: 0.5;
    transition: all 0.2s ease-in-out;
  }
  .arrow-down:hover {
    opacity: 1;
  }
  panel.main .banner {
    padding: 30px 0;
    background: rgba(0, 0, 0, 0.5);
  }
  panel.main .banner .tagline {
    color: #ffd800;
    font-size: 4em;
    text-transform: uppercase;
    text-shadow: 2px 2px #035d75;
    line-height: 1em;
    margin-bottom: -0.2em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
  }
  panel.main .banner .slogan {
    color: #fff;
    line-height: 1.2em;
    font-size: 1.6em;
    letter-spacing: 1px;
    margin-top: 0.75em;
    margin-bottom: 1em;
  }
  panel.main .registerlogin {
    font-size: 1.5em;
    color: #035d75;
    text-transform: uppercase;
    box-shadow: 2px 2px 0px #035d75;
  }
  panel.main .registerlogin:hover {
    color: #fff;
  }
  panel.main .banner .switch {
    margin-top: 0.5em;
    color: #ffd800;
  }
  panel.stats {
    padding: 20px 0;
    background: #000;
  }
  panel.stats .stat {
    display: inline-block;
    margin-right: 10%;
  }
  panel.stats .stat:last-of-type {
    margin-right: 0px;
  }
  panel.stats num {
    display: block;
    font-size: 2.5em;
    line-height: 1.2em;
    color: #ffd800;
    font-weight: bold;
    letter-spacing: 0.5px;
  }
  panel.stats type {
    display: block;
    color: #fff;
  }

  panel.why .whys {
    width: 100%;
    text-align: center;
  }
  panel.why incell {
    display: inline-block;
    width: 200px;
    text-align: center;
  }
  panel.why .whyimg {
    width: 200px;
    height: 200px;
    border-radius: 100px;
    background: #abcdef no-repeat center center;;
    background-size: 150%;
    position: relative;
    overflow: hidden;
    margin-bottom: 15px;
  }
  panel.why .whyimg1 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/studentwhy1.jpg'); }
  panel.why .whyimg2 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/studentwhy2.jpg'); }
  panel.why .whyimg3 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/why1.jpg'); }
  panel.why .whyroll {
    width: 100%;
    height: 100%;
    background: rgba(30, 80, 95, 0.8);
    display: table;
    color: #fff;
    opacity: 0;
    transition: all 0.1s ease-in-out;
    border-radius: 50%;
    font-size: 1em;
  }
  panel.why .whyroll:hover {
    opacity: 1;
  }
  panel.why .whyroll .cell {
    padding: 20px;
  }
  panel.why .whytext {
    text-transform: uppercase;
    font-size: 1.25em;
    font-weight: 700;
    line-height: 1.1em;
  }

  panel.backedby {
    background-color: #ffd800;
  }
  panel.backedby .content {
    position: relative;
  }
  panel.backedby .backedlogo {
    background: transparent no-repeat center center;
    background-size: contain;
    height: 150px;
    width: 15%;
    display: inline-block;
    vertical-align: top;
  }
  panel.backedby .backedlogo1 {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/upenn.png');
  }
  panel.backedby .backedlogo2 {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/yale.png');
    background-size: 80%;
  }
  panel.backedby .line1 {
    font-size: 2em;
    color: #035d75;
    line-height: 1.2em;
  }
  panel.backedby .line2 {
    font-size: 2.5em;
    line-height: 1.1em;
  }
  panel.backedby .backedtext {
    margin-top: 20px;
    width: 60%;
    display: inline-block;
    font-family: 'BebasNeue', sans-serif;
    text-transform: uppercase;
    font-weight: bold;
  }
  panel.how {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/castle.jpg') no-repeat center 80%;
    background-size: cover;
    background-attachment: fixed;
    min-height: 300px;
    padding: 0;
  }
  panel.how > div:first-of-type {
    background: rgba(88, 151, 172, 0.6);
    padding-top: 50px;
  }
  panel.how .steps {
    color: #ffd800;
  }
  panel.how .steps {
    width: 100%;
    border-spacing: 30px;
    position: relative;
  }
  panel.how .step {
    display: inline-block;
    width: 20%;
    min-width: 160px;
    margin: 0 30px 50px 0;
  }
  panel.how .howimg {
    padding: 40px 0;
    background: #eed033;
    width: 100%;
  }
  panel.how .howimg img{
    height: 8em;
  }
  panel.how .howtxt {
    padding: 10px;
    background: #fdfbe9;
    font-weight: bold;
    color:black;
  }
  panel.how #join {
    padding: 50px;
  }
  panel.how #join headline{
    color:white;
  }
</style>

<script>
  $(function() {


    $('.arrow-down').click(function() {
      scrollTo('panel.why');
    });
  });
</script>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">MORE INTERNSHIPS, MORE HOUSING<br>AND LESS HASSLE</div>
        <div class="slogan">
          Maximize your next summer experience with your one-stop shop for internships and housing.
          <div style="font-size: 0.7em;"><br />Verify your ".edu" email address to get started! It's completely free!</div>
        </div>
        <a href="register.php<?php if (!is_null($r = View::get('r'))) echo "?r=$r"; ?>">
          <input type="button" class="registerlogin" value="Get Started" />
        </a>
        <div class="switch"><a href="../employers">switch to RECRUITER</a></div>
      </div>
    </div>
  </div>
  <div class="arrow-down"></div>
</panel>
<panel class="stats">
  <div class="content">
    <div class="stat"><num><?php View::echof('users'); ?></num><type>users</type></div>
    <div class="stat"><num><?php View::echof('universities'); ?></num><type>universities</type></div>
    <div class="stat"><num><?php View::echof('jobs'); ?></num><type>jobs</type></div>
    <div class="stat"><num><?php View::echof('sublets'); ?></num><type>sublets</type></div>
    <div class="stat"><num><?php View::echof('companies'); ?></num><type>companies</type></div>
  </div>
</panel>
<panel class="why">
  <div class="content">
    <headline style="color: #035d75;">Why SubLite?</headline>
    <div style="font-size: 1em; margin-top: -20px; margin-bottom: 20px;">
      We know you are working hard to network and get top grades for the best summer experience.<br />
      Finding jobs and housing can be a painstaking process.<br />
      Living in a foreign city over the summer by yourself can be daunting.<br />
      But it doesn't have to be.
    </div>
    <table class="whys"><tr>
      <td class="whycell"><incell>
        <div class="whyimg whyimg1">
          <div class="whyroll"><div class="cell">Lease and sublet verified housing with other college students.</div></div>
        </div>
        <div class="whytext">Verified Peer-to-Peer Housing Network</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg2">
          <div class="whyroll"><div class="cell">Find and be matched to companies and start-ups across the country.</div></div>
        </div>
        <div class="whytext">Internship and Full-Time Job Opportunities</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg3">
          <div class="whyroll"><div class="cell">Have your profile available to many companies</div></div>
        </div>
        <div class="whytext">Streamlined Job Application Process</div>
      </incell></td>
    </tr></table>
    <iframe src="https://www.youtube.com/embed/LY_JB8zc0lk" scrolling="no" allowTransparency="true" frameborder="0" style="max-width: 900px; max-height: 400px; height: 400px; margin-top: 50px;"></iframe>
  </div>
</panel>
<panel class="backedby">
  <div class="content" style="position: relative;">
    <div class="backedlogo backedlogo1"></div>
    <div class="backedtext">
      <div class="line1">SubLite is backed by:</div>
      <div class="line2">Yale Venture Creation Program &amp; Wharton Innovation Fund</div>
    </div>
    <div class="backedlogo backedlogo2"></div>
  </div>
</panel>
<panel class="how">
  <div> <!-- tint wrapper -->
    <div class="content" id="howitworks">
      <headline style="color: #ffd800">How It Works</headline>
      <div class="steps">
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/community.png"/>
          </div>
          <div class="howtxt">
            <center>Join the <br>SubLite Community</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/profile.png"/>
          </div>
          <div class="howtxt">
            <center>Create Your <br>Profile</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/searchandapply.png"/>
          </div>
          <div class="howtxt">
            <center>Search and <br>Apply</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/summersuccess.png"/>
          </div>
          <div class="howtxt">
            <center>Summer <br>Success!</center>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content" id="join">
    <headline>JOIN OUR TEAM!</headline>
    <a href="jobs/job?id=561576fcd83594905f7eb765">
      <input type="button" class="registerlogin" value="Details" />
    </a>
  </div>
</panel>
