<style>
  panel.form {
    background: #fafafa;
  }

  questiontemplate {
    display: none;
  }

  question {
    display: block;
    padding: 0.7em 2em;
    background: #fff;
    margin: 1px;
    position: relative;
  }

  form {
    margin-top: 50px;
  }

  .companyname {
    margin-top: -20px;
    font-size: 1.5em;
  }

  .jobapplicationtitle {
    font-size: 1.5em;
    font-weight: bold;
  }

  #savebutton {
    background: #d5d5d5;
  }

  #fail {
    color: red;
    display: none;
  }

  #success {
    color: green;
    display: none;
  }
</style>

<script>
  function checkform () {
    var errors = "";
    var blankFields = $('textarea').filter(function () {
      return $.trim($(this).val()) === "";
    });
    if (blankFields.length) {
      $('#fail').css("display", "inline-block");
      $('#success').css("display", "none");
      return false;
    }
    return true;
  }
  $(function() {
    $('.save').click(function () {
      $('#fail').css("display", "none");
      $('#success').css("display", "inline-block");
      var questions = [];
      $('textarea').each(function() {
        var question = {_id : $(this).attr('id'), answer : $(this).val()}
        questions.push(question);
      });
      $.post('', {questions: questions}, function (data) {
         console.log('saved!');
      });
    });
  });
</script>

<panel class="form">
  <headline><?php View::echof('jobtitle'); ?></headline>
  <div class="companyname"><?php View::echof('companytitle'); ?></div>
  <div class="content">
    <form method="post" onsubmit="return confirm('Are you sure you want to submit? You cannot undo this.');">
      <left>
        <div class="jobapplicationtitle">Job Application</div><br />
        <br />
        <?php
          foreach(View::get('questions') as $question) {
            $id = $question['id'];
            $text = $question['text'];
            $response = $question['response'];
            echo $text . '<br />';
            if (View::get('submitted')) {
              echo $response . '<br />';
            } else {
              echo "<textarea id=\"$id\" name=\"$id\" required>$response</textarea>";
            }
          }
        ?>
        <?php if (!View::get('submitted')) { ?>
          <input type="submit" id="submit" value="Apply Now" />
          <input type="button" id="savebutton" class="save" value="Save" />
          <div id="success">Application Saved!</div>
          <div id="fail">Please fill out all questions.</div>
        <?php } else {?>
          <div style="color: green;">Your application has been submitted.</div>
        <?php } ?>
      </left>
    </form>
  </div>
</panel>