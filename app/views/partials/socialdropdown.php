<style>
  #socialdropdown {
    background: #ffd800;
    padding: 100px;
    text-align: center;
  }
  .sdtitle {
    text-transform: uppercase;
    font-size: 4em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
    line-height: 1.2em;
  }
  .sdinfo {
    font-size: 1.2em;
    line-height: 2em;
    margin-top: 2em;
  }
  #nothanks {
    opacity: 0.5;
  }
  #sdcityform {
    width: 400px;
  }
  #sdcityform label {
    text-align: center;
  }
  .sdbutton input {
    display: inline-block;
    width: 49%;
  }
</style>

<div id="socialdropdown">
  <div class="sdtitle">Social Hubs is Coming</div>
  <div class="sdinfo">Where will you be this summer?</div>
  <form id="sdcityform" method="post" action="hubs/start.php">
    <div class="form-slider">
      <label for="sdcity">(eg. New York, Boston, San Francisco)</label>
      <input type="text" id="sdcity" name="city" required />
    </div>
    <div class="sdbutton">
      <input type="submit" name="signup" value="Sign Up" />
      <input type="button" value="No Thanks" id="nothanks" />
    </div>
  </form>
</div>

<script>
  if (localStorage.socialdropdown) $('#socialdropdown').hide();

  function nothanks() {
    localStorage.setItem('socialdropdown', true);
  }
  $('#nothanks').click(function() {
    $('#socialdropdown').slideUp(200, 'easeInOutCubic');
    nothanks();
  });
  $('#sdcityform').submit(function() {
    nothanks();
  });
</script>