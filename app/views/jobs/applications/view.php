<style>
  .companyname {
    margin-top: -20px;
    font-size: 1.5em;
  }
  .headline {
    font-size: 1.5em;
    margin: 2em 0;
  }

  response, text, answer {
    display: block;
  }
  text {
    font-size: 1.2em;
    line-height: 1.5em;
    margin-top: 1em;
  }
  answer {
    padding: 1em;
  }
</style>

<responsesData class="hide">
  <?php echo json_encode(View::get('responses')); ?>
</responsesData>
<profileData class="hide">
  <?php echo json_encode(View::get('profile')); ?>
</profileData>

<templates>
  <responsetemplate>
    <response>
      <text>{text}</text>
      <answer>{answer}</answer>
    </response>
  </responsestemplate>
</templates>

<script>
  $(function () {
    var responsesData = JSON.parse($('responsesData').html());
    var profileData = JSON.parse($('profileData').html());

    (function setupResponses() {
      var responsesHTML = '';
      responsesData.forEach(function (response) {
        var data = {
          text: response.text,
          answer: response.answer
        };
        responsesHTML += useTemplate('responsetemplate', data);
      });
      $('responses').html(responsesHTML);
    })();
  });
</script>

<panel>
  <div class="content">
    <headline><?php View::echof('jobtitle'); ?></headline>
    <div class="companyname"><?php View::echof('companytitle'); ?></div>

    <left>
      <div class="headline">
        Job application from: <b><?php View::echof('studentname'); ?></b>
      </div>

      <responses></responses>
    </left>
  </div>
</panel>