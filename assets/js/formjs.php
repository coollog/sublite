<script>
  $(function() {
    $('form').submit(function() {
      window.onbeforeunload = null;
    });
  });

  function formunloadmsg(msg) {
    $('input, textarea, select').change(function() {
      window.onbeforeunload = function() { return msg; }
    });
  }

  $(document).ready(function() {    
    if($("#fulltime").is(":checked")){
      $("#durationdiv").hide();
      $("#enddatediv").hide();
    }
  });
  $(document).ready(function(){
    $("#fulltime").click(function(){
      $("#durationdiv").hide(400);
      $("#enddatediv").hide(400);
    });
  });
  $(document).ready(function(){
    $("#internship").click(function(){
      $("#durationdiv").show(400);
      $("#enddatediv").show(400);
    });
  });
  $(document).ready(function() {    
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
  });
  $(document).ready(function() {    
    if($("#locationtype").is(":checked"))
    {
      $("#location").prop('required', false);
      $("#locationdiv").css('visibility','hidden');
    } else {
      $("#location").prop('required', true);
      $("#locationdiv").css('visibility','visible');
    }
  });
  $(function() { 
    $('form').slidinglabels({ 
      /* these are all optional */ 
      className : 'form-slider', // the class you're wrapping the label & input with -> default = slider 
      topPosition : '1em', // how far down you want each label to start 
      leftPosition : '0.5em', // how far left you want each label to start 
      axis : 'x', // can take 'x' or 'y' for slide direction 
      speed : 'fast' // can take 'fast', 'slow', or a numeric value 
    }); 
  });
</script>