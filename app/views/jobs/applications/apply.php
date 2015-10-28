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
    display: inline-block;
  }

  #success {
    color: green;
    display: inline-block;
  }
</style>

<script>
  function checkform () {
    var errors = "";
    var blankFields = $('textarea').filter(function () {
      return $.trim($(this).val()) === "";
    });
    if (blankFields.length) {
      $('#fail').show();
      $('#success').hide();
      return false;
    }
    return true;
  }
  $(function() {
    $('.save').click(function () {
      var questions = [];

      $('textarea').each(function() {
        var question = {_id : $(this).attr('_id'), answer : $(this).val()}
        questions.push(question);
      });
      $.post('', {questions: questions}, function (data) {
        $('#fail').hide();
        $('#success').show();
         console.log('saved!');
         console.log(data);
      });
    });

    $('input, textarea').keypress(function () {
      $('#success').hide();
      $('#hide').hide();
    });

    $('#success, #fail').hide();
  });
</script>

<panel class="form">
  <div class="content">
    <headline><?php View::echof('jobtitle'); ?></headline>
    <div class="companyname"><?php View::echof('companytitle'); ?></div>

    <form method="post"
          onsubmit="return confirm('Are you sure you want to submit? You cannot undo this.');">
      <left>
        <div class="jobapplicationtitle">Job Application</div>
        <br /><br />

        <center style="line-height: 2em; font-style: italic;">
          Your profile and resume will be submitted along with this application.
          <a href="../editprofile" target="_blank">
            <input type="button" value="Edit Profile" />
          </a>
          <a href="../viewprofile" target="_blank">
            <input type="button" value="View Profile" />
          </a>
        </center>
        <?php vnotice(); ?>

        <br /><br />
        <?php
          foreach(View::get('questions') as $question) {
            $_id = $question['_id'];
            $text = $question['text'];
            $answer = $question['answer'];
            echo $text . '<br />';
            if (View::get('submitted')) {
              echo $answer . '<br />';
            } else {
              echo "<textarea _id=\"$_id\" name=\"$_id\" required>$answer</textarea>";
            }
          }
        ?>
        <?php if (!View::get('submitted')) { ?>
          <?php vnotice(); ?>
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