<style>
  panel.main {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/socialmain.jpg') no-repeat top center;
    background-size: cover;
    background-position: center 0;
    /*background-attachment: fixed;*/
    display: table;
    height: 100%;
    padding-bottom: 0;
    position: relative;
    box-sizing: border-box;
  }

  input[type=email] {
    padding: 0.5em 0px 0.5em 0.5em;
  }
  .registerboxbutton {
    float: left;
    display: inline-block;
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
    background: rgba(0, 0, 0, 0);
  }
  panel.main .banner .tagline {
    color: #ffd800;
    font-size: 4em;
    text-transform: uppercase;
    text-shadow: 4px 4px #035d75;
    line-height: 1em;
    margin-bottom: -0.2em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
    font-size: 7em;
  }
  panel.main .banner .slogan {
    color: #fff;
    line-height: 1.2em;
    font-size: 2em;
    letter-spacing: 1px;
    margin-top: 0.25em;
    margin-bottom: 1em;
    text-shadow: 2px 2px #035d75;
    line-height: 1em;
  }
  panel.main .registerlogin {
    font-size: 1.5em;
    color: #035d75;
    border-radius: 5px;
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
    width: 200;
    height: 200px;
    background: #ffffff no-repeat center center;;
    background-size: 100%;
    position: relative;
    overflow: hidden;
    margin-bottom: 15px;
  }
  panel.why .whyimg1 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/hubs.png'); }
  panel.why .whyimg2 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/forums.png'); }
  panel.why .whyimg3 { background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/meetups.png'); }
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
  panel.why .whycell headline {
    margin-bottom: 0.25em;
  }
  panel.backedby {
    background-color: #ffd800;
    padding-top: 75px;
    padding-bottom: 75px;
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
        <div class="tagline">Sign up for the social hub!</div>
        <div class="slogan">
          Get your questions answered and make new friends this summer!
        </div>
          <a href="register.php">
            <input type="button" class="registerlogin" value="Sign up now" />
          </a>
      </div>
    </div>
  </div>
  <div class="arrow-down"></div>
</panel>
<panel class="why">
  <div class="content">
    <headline style="color: #035d75;">Why Join the Social Hub?</headline>
    <div style="font-size: 1em; margin-top: -20px; margin-bottom: 20px;">
      Getting a good internship doesn't guarantee a good summer. Find students of the same university,<br/ >
      interests, or company working in your area and have fun with them! Here are the main features:
    </div>
    <table class="whys"><tr>
      <td class="whycell"><incell>
        <div class="whyimg whyimg1">
        </div>
        <headline>Hubs</headline>
        insert text here
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg2">
        </div>
        <headline>Forums</headline>
        insert text here
      </incell></td>
      <td class="whycell"><incell>
        <div class="whyimg whyimg3">
        </div>
        <headline>Meetups</headline>
        insert text here
      </incell></td>
    </tr></table>
  </div>
</panel>
<panel class="backedby">
  <div class="content" style="position: relative;">
    <div class="backedtext">
      <div class="line2">Subscribe to us to get more information</div>
      <input type="button" class="registerlogin" value="Sign up now" />
    </div>
  </div>
</panel>
