<style>
  panel.form {
    background: #fafafa;
  }

  chosenquestiontemplate {
    display: none;
  }

  question {
    display: block;
    padding: 0.7em 2em;
    background: #fff;
    margin: 1px;
  }
  question op {
    display: inline-block;
    width: 2em;
    height: 2em;
    background: transparent; no-repeat center center;
    background-size: cover;
    margin-bottom: -0.5em;
    float: right;
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
  question op:hover {
    opacity: 0.5;
  }
  question op.plus {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>/assets/gfx/applications/plus.png');
  }
  question op.minus {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>/assets/gfx/applications/minus.png');
  }
  question id {
    display: none;
  }

  writeyourown {
    display: block;
    background: #f1f1f1;
    padding: 0.7em 2em;
  }
</style>

<chosenquestiontemplate>
  <question qid="{_id}">
    <op class="minus"></op>
    <text>{text}</text>
  </question>
</chosenquestiontemplate>

<script>
  function selectQuestion(_id, text) {
    var html = useTemplate('chosenquestiontemplate', {
      'text': text,
      '_id': _id
    });
    $('.chosen').append(html);

    $('question op.minus').off('click').click(function() {
      $(this).parent().slideUp(function () {
        var _id = $(this).attr('qid');
        unselectQuestion(_id);
        $(this).remove();
      });
    });
  }

  function unselectQuestion(_id) {
    $('question.vanilla').each(function() {
      if ($(this).attr('qid') == _id) {
        $(this).slideDown();
      }
    });
  }

  $(function() {
    $('question op.plus').click(function() {
      $(this).parent().slideUp(function () {
        var _id = $(this).attr('qid');
        var text = $(this).children('text').html();
        selectQuestion(_id, text);
      });
    });
  });
</script>

<panel class="form">
  <div class="content">
    <headline>Create Job Application</headline>

    <form>
      <left>
        Choose questions to add to the application (recommended):
        <div class="questionlist">
          <?php
            $vanillaQuestions = View::get('vanillaQuestions');
            foreach ($vanillaQuestions as $question) {
          ?>
              <question qid="<?php echo $question->getId(); ?>" class="vanilla">
                <op class="plus"></op>
                <text><?php echo $question->getText(); ?></text>
              </question>
          <?php
            }
          ?>
        </div>

        <subheadline>- or -</subheadline></small><br />

        <writeyourown>
          <div class="form-slider">
            <label for="custom">Write your own custom questions:</label>
            <input type="text" name="custom" />
          </div>
          (Writing your own question may result in lower applications received.)<br />
        </writeyourown>

        Selected questions:
        <div class="chosen">

        </div>

        <input type="button" name="addcustom" value="Finish" />
      </left>
    </form>
  </div>
</panel>