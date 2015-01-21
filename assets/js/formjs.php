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
</script>