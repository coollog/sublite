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
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/plus.png');
  }
  op.minus {
    background-image: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/minus.png');
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

  #searchText {
    width: 80%;
  }
  #searchCustom {
    display: inline-block;
    padding-left: 1em;
    padding-right: 1em;
    height: 2.1em;
    float: right;
    margin-top: 0.5em;
  }
  .customList {
    max-height: 300px;
    overflow-y: auto;
  }

  #selected {
    padding-bottom: 1em;
  }
  .chosen {
    margin-bottom: 1em;
    cursor: pointer;
  }
  .chosen question:before {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/updown.png') no-repeat center center;
    background-size: contain;
    height: 1em;
    width: 1em;
    display: block;
    position: absolute;
    left: 0.5em;
    top: 1em;
    opacity: 0.5;
    content: "";
  }
</style>

<questiontemplate>
  <question qid="{_id}" class="vanilla" vanilla="{vanilla}" style="{style}">
    <op class="{optype}"></op>
    <text>{text}</text>
  </question>
</questiontemplate>

<vanillaquestionsdata class="hide">
  <?php
    $vanillaQuestions = View::get('vanillaQuestions');
    echo toJSON($vanillaQuestions);
  ?>
</vanillaquestionsdata>
<chosenquestionsdata class="hide">
  <?php
    $chosen = View::get('chosen');
    echo toJSON($chosen);
  ?>
</chosenquestionsdata>

<script>
  function getQuestionFromTemplate(_id, text, elemClass, hide) {
    var style = '';
    if (hide) {
      style = 'display: none;';
    }
    var html = useTemplate('questiontemplate', {
      text: text,
      _id: _id,
      class: elemClass,
      style: style,
      optype: 'plus',
    });
    return html;
  }

  function checkChosen(_id) {
    return $('.chosen question[qid=' + _id + ']').size() > 0;
  }

  function addVanillaQuestion(_id, text, hide) {
    var html;
    if (hide) {
      html = getQuestionFromTemplate(_id, text, 'vanilla', true);
    } else {
      html = getQuestionFromTemplate(_id, text, 'vanilla');
    }
    $('.vanillaList').append(html);
  }

  function addCustomQuestion(_id, text) {
    if (checkChosen(_id)) return;
    var html = getQuestionFromTemplate(_id, text, 'custom');
    $('.customList').append(html);
  }

  function chooseQuestion(_id, text, vanilla) {
    var html = useTemplate('questiontemplate', {
      text: text,
      _id: _id,
      vanilla: vanilla,
      class: '',
      style: 'display: none;',
      optype: 'minus',
    });
    $('.chosen').append(html);
    $('.chosen question[qid=' + _id + ']').slideDown(200, 'easeInOutCubic');

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
        console.log(data);
        remove(question);
      });
    }
  }

  function reshowVanilla(_id) {
    $('question.vanilla').each(function() {
      if ($(this).attr('qid') == _id) {
        $(this).attr('enabled', true).slideDown(200, 'easeInOutCubic');
      }
    });
  }

  function bindQuestionOpClick(listClass, vanilla) {
    $(listClass + ' question op.plus').click(function() {
      var question = $(this).parent();
      if (question.attr('enabled') === 'false') {
        return;
      }
      question.attr('enabled', false).slideUp(function () {
        var _id = $(this).attr('qid');
        var text = $(this).children('text').html();
        chooseQuestion(_id, text, vanilla);
      });
    });
  }

  $(function() {
    // Sorting of chosen questions.
    $('.chosen').sortable({
      placeholder: 'ui-state-highlight',
      start: function(e, ui){
        ui.placeholder.height(ui.item.height());
      }
    });
    $('.chosen').disableSelection();

    // When clicking the plus next to writing own custom question.
    $('#createCustom').click(function() {
      var text = $('#customText').val();

      if ($(this).attr('enabled') === 'false' || text == '') {
        return;
      }
      $(this).attr('enabled', false);
      // Create custom question on backend.

      $.post('../createcustom', {text: text}, function (data) {
        console.log(data);
        chooseQuestion(data, text, false);
        $('#customText').val('');
        $('#createCustom').attr('enabled', true);
      });
    });

    // When clicking search.
    (function setupSearchCustom() {
      $('#searchCustom').click(function() {
        $('.customList').html('');
        var search = $('#searchText').val();
        $.post('../searchcustom', {search: search}, function (data) {
          console.log(data);
          data = JSON.parse(data);
          // Create the custom questions to be able to add to selected.
          if (data.length == 0) {
            $('.customList').html("Your search for '" + search +
                                  "' did not match any questions!");
          } else {
            $('.customList').html('');
          }
          data.forEach(function (questionData) {
            addCustomQuestion(questionData._id, questionData.text);
          });

          // When clicking the plus next to custom questions.
          bindQuestionOpClick('.customList', false);

          $('#searchText').val('');
        });
      });
      callOnEnter('#searchText', function () {
        $('#searchCustom').click();
      });
    })();

    // Load up vanilla questions.
    (function loadVanillaQuestions() {
      var data = JSON.parse($('vanillaquestionsdata').html());
      $('vanillaquestionsdata').remove();
      data.forEach(function (question) {
        question._id = question._id.$id;
        addVanillaQuestion(question._id, question.text, question.hide);
      });
    })();

    // When clicking the plus next to vanilla questions.
    bindQuestionOpClick('.vanillaList', true);

    // Load up selected questions.
    (function loadChosenQuestions() {
      var data = JSON.parse($('chosenquestionsdata').html());
      $('chosenquestionsdata').remove();
      data.forEach(function (question) {
        question._id = question._id.$id;
        chooseQuestion(question._id, question.text, question.vanilla);
      });
    })();

    // Finish creating/editing application.
    $('#finish').click(function () {
      $(this).prop('disabled', true);

      var questionIds = [];
      $('.chosen').children().each(function() {
        var _id = $(this).attr('qid');
        questionIds.push(_id);
      });

      console.log(questionIds);

      $.post('', {saving: true, questionIds: questionIds}, function (data) {
        window.location = '../home';
      });
    });
  });
</script>

<panel class="form">
  <div class="content">
    <headline><?php View::echof('createEdit'); ?> Job Application</headline>
    <subheadline>
      <?php
        View::echof('jobTitle');
        $location = View::get('jobLocation');
        if (!empty($location)) {
          echo " | $location";
        }
      ?>
    </subheadline>

    <form>
      <left>
        <div id="selected">
          <subheadline>Selected questions:</subheadline>
          <i>
            Select questions from below to add to your application.<br />
            <small>
              Applications can have no questions.
              Each applicant's resume and profile will be automatically attached
              to the application.
            </small>
          </i>
          <div class="chosen"></div>
        </div>

        <subheadline>Add questions:</subheadline><br />

        Choose questions to add to the application (recommended):<br />
        <br />
        <div class="vanillaList"></div>

        <subheadline>- or -</subheadline><br />

        Search for questions other recruiters have written:
        <searchcustom>
          <input type="text" id="searchText" />
          <input type="button" id="searchCustom" value="Search" />
        </searchcustom>
        <div class="customList"></div>

        <subheadline>- or -</subheadline><br />

        <writeyourown>
          <div class="form-slider">
            <label for="customText">Write your own custom questions:</label>
            <input type="text" id="customText" />
            <op class="plus" id="createCustom"></op>
          </div>
          (Writing your own question may result in lower applications received.)<br />
        </writeyourown>

        <br /><br />
        <input type="button" id="finish" value="Finish" />
      </left>
    </form>
  </div>
</panel>