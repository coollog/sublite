<script>
  "use strict";

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

  /**
   * Calls function 'onEnter' when Enter is pressed on $(selector).
   */
  function callOnEnter(selector, onEnter) {
    $(selector).off('keypress').keypress(function (e) {
      if (e.which == 13) {
        onEnter(this);
      }
    });
  }

  /**
   * Simulates Enter keypress on $(selector).
   */
  function triggerEnter(selector) {
    var e = jQuery.Event('keypress');
    e.which = 13;
    $(selector).trigger(e);
  }
</script>