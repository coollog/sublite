<script>
  function formunloadmsg(msg) {
    $('input, textarea, select').change(function() {
      window.onbeforeunload = function() { return msg; }
    });
  }
  function formunloadjobsmsg(msg) {
    $('input, fields, items, textarea, select').change(function() {
      window.onbeforeunload = function() { return msg; }
    });
  }
  function formunloadfunction(f) {
    $('input, textarea, select').change(function() {
      window.onbeforeunload = function() { f(); }
    });
  }
  function formJSON(f) {
    return $(f).serializeObject();
  }
  function formReset(form) {
    $(form).children('.error, .success').hide();

    form.reset();
    $(form).find('.img').html('');
  }
  $.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
      if (o[this.name] !== undefined) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };

  // jQuery plugin to prevent double submission of forms
  jQuery.fn.preventDoubleSubmission = function() {
    $(this).on('submit',function(e){
      var $form = $(this);

      if ($form.data('submitted') === true) {
        // Previously submitted - don't submit again
        e.preventDefault();
      } else {
        // Mark it so that the next submit can be ignored
        $form.data('submitted', true);
      }
    });

    // Keep chainability
    return this;
  };

  $(function() {
    $('form').submit(function() {
      window.onbeforeunload = null;
    });
    $('form:not(.js-allow-double-submission)').preventDoubleSubmission();

    formSetup();
  });

  function formSetup() {
    $('form').slidinglabels({
      /* these are all optional */
      className : 'form-slider', // the class you're wrapping the label & input with -> default = slider
      topPosition : '1em', // how far down you want each label to start
      leftPosition : '0.5em', // how far left you want each label to start
      axis : 'x', // can take 'x' or 'y' for slide direction
      speed : 'fast' // can take 'fast', 'slow', or a numeric value
    });

    /* JQUERY UI STUFF */
    $('.datepicker').datepicker({
      onSelect: function(dateText, inst) {
        // $('<style id="nodatepicker">.ui-datepicker { visibility: hidden !important; }</style>').appendTo('body');
        // setTimeout(function() {
        //   $('.datepicker').focus().datepicker('hide');
        //   setTimeout(function() {
        //     $('#nodatepicker').remove();
        //   }, 1000);
        // }, 500);
      }
    });
    $('.timepicker').timepicker({ 'scrollDefault': 'now' });

    $('.sliderrange').each(function() {
      var min = parseInt($(this).attr('min')),
          max = parseInt($(this).attr('max')),
          minfield = $(this).attr('minfield'),
          maxfield = $(this).attr('maxfield'),
          minval = parseInt($(minfield).val()) || 0,
          maxval = parseInt($(maxfield).val()) || 0,
          values = [minval, maxval];

      $(this).slider({
        range: true,
        min: min,
        max: max,
        values: values,
        slide: function( event, ui ) {
          $(minfield).val(ui.values[0]).focus();
          $(maxfield).val(ui.values[1]).focus();
          $(minfield + 'after').children().first().html(ui.values[0]);
          $(minfield + 'after').children().last().html(ui.values[1]);
        }
      });
    });

    $('.slidermin').each(function() {
      var min = parseInt($(this).attr('min')),
          max = parseInt($(this).attr('max')),
          field = $(this).attr('field'),
          value = parseInt($(field).val()) || 0;

      $(this).slider({
        range: "min",
        min: min,
        max: max,
        value: value,
        slide: function( event, ui ) {
          $(field).val(ui.value).focus();
          $(field + 'after').children().first().html(ui.value);
        }
      });
    });
  }
</script>