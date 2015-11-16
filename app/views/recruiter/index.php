<style>
  panel.main {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/main.jpg') no-repeat top center;
    background-size: cover;
    background-position: center 0px;
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
    line-height: 2.5em;
    font-size: 1.6em;
    letter-spacing: 1px;
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
  panel.why .whyimg1 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/why1.jpg'); }
  panel.why .whyimg2 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/why2.jpg'); }
  panel.why .whyimg3 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/why3.jpg'); }
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
    font-size: 0.8em;
    font-weight: 700;
    line-height: 1.1em;
  }
  panel.how {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/how.jpg') no-repeat center 80%;
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
    border-spacing: 15px;
    position: relative;
  }
  panel.how .step {
    display: inline-block;
    width: 10%;
    min-width: 160px;
    margin: 0 10px 50px 0;
  }
  panel.how .howimg {
    padding: 20px 0;
    background: #eed033;
    width: 100%;
  }
  panel.how .howimg img{
    height: 6em;
  }
  panel.how .howtxt {
    padding: 5px;
    background: #fdfbe9;
    font-weight: bold;
    color:black;
    font-size: .7em;
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
        <div class="tagline">Student Recruitment, Reimagined.</div>
        <div class="slogan">Attract the New Generation Talent with your Company's Unique Personality.</div>
        <a href="<?php echo $GLOBALS['dirpre']; ?>../employers/register">
          <input type="button" class="registerlogin" value="Get Started" />
        </a>
        <div class="switch"><a href="../jobs">switch to STUDENT</a></div>
      </div>
    </div>
  </div>
  <div class="arrow-down"></div>
</panel>
<panel class="why">
  <div class="content">
    <headline style="color: #000;">Why Choose Us?</headline>
    <table class="whys"><tr>
      <td class="whycell"><incell>
        <div class="whyimg whyimg1">
          <div class="whyroll"><div class="cell">Our network includes 8000 students from nearly 600 universities and is growing everyday.</div></div>
        </div>
        <div class="whytext">Access a growing network of talented university students.</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg2">
          <div class="whyroll"><div class="cell">Students can discover qualities about your company that they cannot find elsewhere.</div></div>
        </div>
        <div class="whytext">Showcase the personality of your company</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg3">
          <div class="whyroll"><div class="cell">Build a custom application from our suggested questions or design your own unique to your company.</div></div>
        </div>
        <div class="whytext">Streamline Your Hiring Process</div>
      </incell></td>
    </tr></table>
  </div>
</panel>
<panel class="how">
  <div> <!-- tint wrapper -->
    <div class="content" id="howitworks">
      <headline style="color: #ffd800">How It Works</headline>
      <div class="steps">
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/briefcase.png"/>
          </div>
          <div class="howtxt">
            <center>Create Job<br>Listings & Profile</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/profile.png"/>
          </div>
          <div class="howtxt">
            <center>Track the Analytics of<br>Your Listing</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/credits.png"/>
          </div>
          <div class="howtxt">
            <center>Redeeem Credits to <br>View Applicants</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/message.png"/>
          </div>
          <div class="howtxt">
            <center> Message Students<br>Directly</center>
          </div>
        </div>
        <div class="step">
          <div class="howimg">
            <img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/medal.png"/>
          </div>
          <div class="howtxt">
            <center>Find Your Ideal<br>Candidate</center>
          </div>
        </div>
      </div>
    </div>
  </div>
</panel>
