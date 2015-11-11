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
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/how.jpg') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    min-height: 300px;
  }
  panel.how .headline {
    color: #ffd800;
  }
  panel.how .steps {
    width: 100%;
    border-spacing: 10px;
    border-collapse: separate;
  }
  panel.how .step {
    background: #fff;
    -webkit-transform: skew(10deg);
     -moz-transform: skew(10deg);
       -o-transform: skew(10deg);
    height: 3em;
    padding: 10px;
    cursor: pointer;
    font-weight: 700;
    width: 16%;
  }
  panel.how .step table {
    -webkit-transform: skew(-10deg);
     -moz-transform: skew(-10deg);
       -o-transform: skew(-10deg);
  }
  panel.how .step.active {
    background: #ffd800;
  }
  panel.how .step.active .bignum {
    color: #035d75;
  }
  panel.how .bignum {
    font-size: 2em;
    color: #ffd800;
  }
  panel.how .steptext {
    text-align: left;
    padding-left: 10px;
  }
  panel.how .desc {
    text-align: left;
    background: rgba(255, 216, 0, 0.8);
    padding: 20px 40px;
    display: none;
    font-size: 1em;
  }
</style>

<script>
  $(function() {
    function getStep(q) {
      return parseInt($(q).find('.bignum').html());
    }
    var step = null;
    $('panel.how .step').hover(function() {
      $(this).addClass('active');
    }, function() {
      if (step != getStep(this)) {
        $(this).removeClass('active');
      }
    }).click(function() {
      scrollTo(this);
      if (step == getStep(this)) {
        $(this).removeClass('active');
        step = null;
        $('.desc').slideUp(200, 'easeInOutCubic');
      } else {
        $('panel.how .step').removeClass('active');
        $(this).addClass('active');
        step = getStep(this);
        $('.desc').each(function() {
          if ($(this).attr('num') == step)
            $(this).slideDown(200, 'easeInOutCubic');
          else
            $(this).slideUp(200, 'easeInOutCubic');
        });
      }
    }).hover(function() {
      if (step == null) $(this).click();
    }, function() {});

    $('.arrow-down').click(function() {
      scrollTo('panel.why');
    });
  });
</script>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">One-Stop Shop for Internships and Housing!</div>
        <div class="slogan">
          Find reliable housing, cool internships, and meet other university students in your area with SubLite!
          <div style="font-size: 0.7em;"><br />Verify your ".edu" email address to get started! It's completely free!</div>
        </div>
        <a href="register.php<?php if (!is_null($r = vget('r'))) echo "?r=$r"; ?>">
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
    <div class="stat"><num><?php vecho('users'); ?></num><type>users</type></div>
    <div class="stat"><num><?php vecho('universities'); ?></num><type>universities</type></div>
    <div class="stat"><num><?php vecho('jobs'); ?></num><type>jobs</type></div>
    <div class="stat"><num><?php vecho('sublets'); ?></num><type>sublets</type></div>
    <div class="stat"><num><?php vecho('companies'); ?></num><type>companies</type></div>
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
          <div class="whyroll"><div class="cell">Sublet from other students and connect directly with recruiters.</div></div>
        </div>
        <div class="whytext">Verified housing and job listings</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg2">
          <div class="whyroll"><div class="cell">Meet up with other students working in your area and get all your questions answered in our hub-based forums.</div></div>
        </div>
        <div class="whytext">Connect with Other Students</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg3">
          <div class="whyroll"><div class="cell">Only students with university .edu emails can register for an account.</div></div>
        </div>
        <div class="whytext">Exclusively for students</div>
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
  <div class="content">
    <headline style="color: #ffd800">How It Works</headline>
    <table class="steps"><tr>
      <td class="step"><table><tr>
        <td class="bignum">1</td>
        <td class="steptext">Register</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">2</td>
        <td class="steptext">Search for Internships</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">3</td>
        <td class="steptext">Contact Recruiters</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">4</td>
        <td class="steptext">Secure Internship</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">5</td>
        <td class="steptext">Sublet</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">6</td>
        <td class="steptext">Summer Success!</td>
      </tr></table></td>
    </tr></table>
    <div class="desc" num="1">Use your .edu email address to create a SubLite profile. Connect exclusively with other university students.</div>
    <div class="desc" num="2">Our enhanced listing portal allows you to search for jobs by geographic region, industry, dates, and more. Unlike plain listings, SubLite allows companies to highlight their culture &mdash; allowing you to make an informed decision based on criteria you care about.</div>
    <div class="desc" num="3">Our internal messaging system allows you to contact recruiters directly.</div>
    <div class="desc" num="4">Land that internship you want!</div>
    <div class="desc" num="5">Now that you know your summer location, you may need to either find housing or sublet your apartment while away. Our platform gives you the peace of mind of subletting exclusively to students. Show off your place by highlighting its amenities and uploading photos. Negotiate directly with other students on the final terms &mdash; there is no fee!</div>
    <div class="desc" num="6">With a job and housing secured, you are set for a stress-free summer!</div>
  </div>
</panel>
