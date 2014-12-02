<style>
  html, body {
    height: 100%;
    margin: 0;
    font-size: 14px;
  }
  navbar {
    background: #000;
    border-bottom: 3px solid #ffd800;
    height: 50px;
    display: block;
    box-sizing: border-box;
    padding: 10px 20px;
    line-height: 1.5em;
    font-size: 1.5em;
    color: #fff;
    overflow: hidden;
  }
  panel {
    display: block;
    width: 100%;
    text-align: center;
    padding: 50px 0;
  }
  .content {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 50px;
  }
  .cell {
    display: table-cell;
    vertical-align: middle;
  }
  headline {
    display: block;
    font-size: 2em;
    text-transform: uppercase;
    margin-bottom: 25px;
  }
  panel.main {
    background: #cecacb no-repeat center center;;
    background-size: cover;
    display: table;
    height: 80%;
  }
  panel.main .banner {
    padding: 30px 0;
    background: rgba(0, 0, 0, 0.5);
  }
  panel.main .banner .switch {

  }
  panel.why .whys {
    width: 100%;
    text-align: center;
  }
  panel.why incell {
    display: inline-block;
    width: 150px;
    text-align: center;
  }
  panel.why .whyimg {
    width: 150px;
    height: 150px;
    border-radius: 75px;
    background: #abcdef no-repeat center center;;
    background-size: cover;
    position: relative;
    overflow: hidden;
    margin-bottom: 15px;
  }
  panel.why .whyroll {
    width: 100%;
    height: 100%;
    background: rgba(30, 80, 95, 0.5);
    display: table;
    color: #fff;
    opacity: 0;
    transition: all 0.1s ease-in-out;
    border-radius: 50%;
  }
  panel.why .whyroll:hover {
    opacity: 1;
  }
  panel.how {
    background: #fedcba no-repeat center center;
    background-size: cover;
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
  }
  panel.how .step:hover {
    background: #ffd800;
  }
  panel.how .step:hover .bignum {
    color: #035d75;
  }
  panel.how .bignum {
    font-size: 2em;
    color: #ffd800;
  }
  panel.how .desc {
    text-align: left;
    background: rgba(255, 216, 0, 0.8);
    padding: 20px 40px;
  }
  footer {
    background: #000;
    height: 100px;
  }
</style>

<navbar>
  SubLite
</navbar>
<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">Student Recruitment, Reimagined.</div>
        <div class="slogan">Attract the New Generation Talent with your Company's Unique Personality.</div>
        <div class="registerlogin">Register / Log In</div>
        <div class="switch">switch to STUDENT</div>
      </div>
    </div>
  </div>
</panel>
<panel class="why">
  <div class="content">
    <headline>Why Choose Us?</headline>
    <table class="whys"><tr>
      <td class="whycell"><incell>
        <div class="whyimg">
          <div class="whyroll"><div class="cell">Rollover text</div></div>
        </div>
        <div class="whytext">Access a growing network of talented university students.</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg">
          <div class="whyroll"><div class="cell">Rollover text</div></div>
        </div>
        <div class="whytext">Showcase the personality of your company</div>
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg">
          <div class="whyroll"><div class="cell">Rollover text</div></div>
        </div>
        <div class="whytext">Design a candid and creative job description</div>
      </incell></td>
    </tr></table>
  </div>
</panel>
<panel class="how">
  <div class="content">
    <headline>How It Works</headline>
    <table class="steps"><tr>
      <td class="step"><table><tr>
        <td class="bignum">1</td>
        <td class="steptext">Recruiter Registration</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">2</td>
        <td class="steptext">Company Profile</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">3</td>
        <td class="steptext">Job Listing</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">4</td>
        <td class="steptext">Data Analysis</td>
      </tr></table></td>
      <td class="step"><table><tr>
        <td class="bignum">5</td>
        <td class="steptext">Direct Contact</td>
      </tr></table></td>
    </tr></table>
    <div class="desc">PLACEHOLDER</div>
  </div>
</panel>
<footer></footer>