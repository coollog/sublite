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
  op {
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
  op:hover {
    opacity: 0.5;
  }
  op.plus {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>/assets/gfx/applications/plus.png');
  }
  op.minus {
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

  #customText {
    width: 80%;
  }
  #createCustom {
    margin-top: 0.75em;
  }
</style>

<chosenquestiontemplate>
  <question qid="{_id}" vanilla="{vanilla}" style="display: none;">
    <op class="minus"></op>
    <text>{text}</text>
  </question>
</chosenquestiontemplate>

<script>
  function addQuestion(_id, text, vanilla) {
    var html = useTemplate('chosenquestiontemplate', {
      'text': text,
      '_id': _id,
      'vanilla': vanilla,
    });
    $('.chosen').append(html);
    $('.chosen question[qid=' + _id + ']').slideDown();

    $('question op.minus').off('click').click(function() {
      var question = $(this).parent();
      var vanilla = question.attr('vanilla') === 'true';
      removeQuestion(question, vanilla);
    });
  }

  function removeQuestion(question, vanilla) {
    function remove(question, afterSlide) {
      question.slideUp(function () {
        if (typeof afterSlide !== 'undefined') {
          afterSlide();
        }
        $(this).remove();
      });
    }

    var _id = question.attr('qid');
    if (vanilla) {
      // The question to remove is a vanilla question, so just reshow it the
      // vanilla list.
      remove(question, function () {
        reshowVanilla(_id);
      });
    } else {
      // Custom question removal should call deleteCustom on backend.
      $.post('../deletecustom', {
        questionId: _id,
        jobId: '<?php View::echof('jobId'); ?>',

      }, function (data) {
        remove(question);
      });
    }
  }

  function reshowVanilla(_id) {
    $('question.vanilla').each(function() {
      if ($(this).attr('qid') == _id) {
        $(this).attr('enabled', true).slideDown();
      }
    });
  }

  $(function() {
    $('.vanillalist question op.plus').click(function() {
      var question = $(this).parent();
      if (question.attr('enabled') === 'false') {
        return;
      }
      question.attr('enabled', false).slideUp(function () {
        var _id = $(this).attr('qid');
        var text = $(this).children('text').html();
        addQuestion(_id, text, true);
      });
    });

    $('#createCustom').click(function() {
      var text = $('#customText').val();

      if ($(this).attr('enabled') === 'false' || text == '') {
        return;
      }
      $(this).attr('enabled', false);
      // Create custom question on backend.

      $.post('../createcustom', {text: text}, function (data) {
        addQuestion(data, text, false);
        $('#customText').val('');
        $('#createCustom').attr('enabled', true);
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
        <div class="vanillalist">
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
            <label for="customText">Write your own custom questions:</label>
            <input type="text" name="custom" id="customText" />
            <op class="plus" id="createCustom"></op>
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