<script>
  function scrollTo(q, n) {
    var top = $(q).offset().top;
    if (typeof n !== 'undefined') {
      top = $($(q)[n]).offset().top;
    }
    $('html, body').finish().animate({
      scrollTop: top
    }, 200);
  }

  /**
   * Uses the html in element 'templateName' and replaces {key} with value for
   * each key in data.
   */
  function useTemplate(templateName, data) {
    var html = $(templateName).html();
    for (var key in data) {
      var value = data[key];
      var re = new RegExp('{' + key + '}', 'g');
      html = html.replace(re, value);
    }
    return html;
  }
</script>