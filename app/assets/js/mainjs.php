<script>
  function scrollTo(q) {
    $('html, body').finish().animate({
      scrollTop: $(q).offset().top
    }, 200);
  }
</script>