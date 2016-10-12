<link rel="stylesheet" type="text/css" href="public/app.css">

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">MORE INTERNSHIPS, MORE HOUSING<br>AND LESS HASSLE</div>
        <div class="slogan">
          Maximize your next summer experience with your one-stop shop for internships and housing.
          <div style="font-size: 0.7em;"><br />Verify your ".edu" email address to get started! It's completely free!</div>
        </div>
        <a href="register<?php if (!is_null($r = View::get('r'))) echo "?r=$r"; ?>">
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

<script src="<?php echo $GLOBALS["dirpre"]; ?>../public/app.js"></script>
<script>require('views/student/index.js');</script>
