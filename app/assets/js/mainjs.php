<script>
  function scrollTo(q, n) {
    var top = $(q).offset().top;
    if (typeof n !== undefined) {
      top = $($(q)[n]).offset().top;
    }
    $('html, body').finish().animate({
      scrollTop: top
    }, 200);
  }
</script>