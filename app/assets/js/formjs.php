<script>
  function formunloadmsg(msg) {
    $('input, textarea, select').change(function() {
      window.onbeforeunload = function() { return msg; }
    });
  }

  $(function() {

    $('form').submit(function() {
      window.onbeforeunload = null;
    });


    if($("#fulltime").is(":checked")){
      $("#durationdiv").hide();
      $("#enddatediv").hide();
    }

    $("#fulltime").click(function(){
      $("#durationdiv").hide(400);
      $("#enddatediv").hide(400);
    });

    $("#internship").click(function(){
      $("#durationdiv").show(400);
      $("#enddatediv").show(400);
    });

    $('#locationtype').click(function() {
      var tmpLocation = $("#location").attr('value');
      if($(this).is(":checked"))
      {
        tmpLocation = $("#location").attr('value');
        $("#location").val('');
        $("#location").prop('required', false);
        $("#locationdiv").fadeTo(400, 0);
        $("#locationdiv").css('visibility','hidden');
      } else {
        $("#location").prop('required', true);
        $("#locationdiv").css('visibility','visible');
        $("#locationdiv").fadeTo(400, 1);
        $("#location").val(tmpLocation);      
      }
    });

    if($("#locationtype").is(":checked"))
    {
      $("#location").prop('required', false);
      $("#locationdiv").css('visibility','hidden');
    } else {
      $("#location").prop('required', true);
      $("#locationdiv").css('visibility','visible');
    }

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
        $('<style id="nodatepicker">.ui-datepicker { visibility: hidden !important; }</style>').appendTo('body');
        setTimeout(function() {
          $('.datepicker').focus().datepicker('hide');
          setTimeout(function() {
            $('#nodatepicker').remove();
          }, 1000);
        }, 500);
      }
    });

    $('.sliderrange').each(function() {
      var min = $(this).attr('min'),
          max = $(this).attr('max'),
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
      var min = $(this).attr('min'),
          max = $(this).attr('max'),
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
  });
</script>