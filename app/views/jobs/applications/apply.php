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

  #save {
    background: #d5d5d5;
  }
</style>

<panel class="form">
  <headline><?php View::echof('jobtitle'); ?></headline>
  <div class="companyname"><?php View::echof('companytitle'); ?></div>
  <div class="content">
    <form method="post">
      <left>
        <div class="jobapplicationtitle">Job Application</div><br />
        <br />
        <?php
          foreach(View::get('questions') as $question) {
            $id = $question['id'];
            $text = $question['text'];
            $response = $question['response'];
            echo "$text<textarea id=\"$id\" name=\"$id\" required>$response</textarea>";
          }
        ?>
        <input type="button" id="submit" value="Apply Now" />
        <input type="button" id="save" value="Save" />
      </left>
    </form>
  </div>
</panel>