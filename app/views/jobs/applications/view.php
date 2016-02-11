<style>
  .companyname {
    margin-top: -20px;
    font-size: 1.5em;
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

<textarea id="responsesData" class="hide"><?php View::echof('responses'); ?></textarea>

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
    var responsesData = JSON.parse($('#responsesData').html());

    Templates.init();

    (function setupResponses() {
      var responsesHTML = '';
      responsesData.forEach(function (response) {
        response.text = $('<div/>').html(response.text).text();
        response.answer = $('<div/>').html(response.answer).text();
        var data = {
          text: response.text,
          answer: response.answer
        };
        responsesHTML += Templates.use('responsetemplate', data);
      });
      $('responses').html(responsesHTML);
    })();
  });
</script>

<panel>
  <div class="content">
    <headline><?php View::echof('jobtitle'); ?></headline>
    <div class="companyname"><?php View::echof('companytitle'); ?></div>

    <?php
      $isStudent = View::get('isStudent');
      $isRecruiter = View::get('isRecruiter');
      if ($isStudent || $isRecruiter) {
        echo '<br /><br />';
        $studentId = View::get('studentId').'';
        $recruiterId = View::get('recruiterId').'';
        if ($isStudent) {
          $from = $studentId;
          $to = $recruiterId;
          $toText = 'Recruiter';
        }
        if ($isRecruiter) {
          $from = $recruiterId;
          $to = $studentId;
          $toText = 'Student';
        }
        View::partial('newmessage', [
          'from' => $from,
          'to' => $to,
          'text' => "Message $toText"
        ]);
        if ($isStudent) {
          echo View::linkTo('<input type="button" value="See More Jobs" />',
                            '../search',
                            ['byrecruiter' => $recruiterId]);
        }
      }
    ?>

    <left style="margin-top: 2em;">
      <div class="headline">
        Job application from: <b><?php View::echof('studentname'); ?></b>
      </div>

      <responses></responses>
    </left>
  </div>
</panel>