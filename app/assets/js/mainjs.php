<script>
  "use strict";

  $(function () {
    $('.hide, templates').hide();
    setTimeout(function() {
      $('.form-slider > input').each(function() {
        if ($(this).val()) {
          $(this).focus().blur();
        }
      });
    }, 200);
  });

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
   * DEPRECATED, use the Templates class below.
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
   * Call init() on document.ready().
   * Call use() to make html.
   */
  var Templates = {
    templateHTML: {},
    error: "We're sorry, something went wrong here. Try refreshing the page.",

    init: function () {
      var self = this;

      $('templates').each(function () {
        $(this).children().each(function() {
          var name = $(this).prop("tagName").toLowerCase();
          var html = $(this).html();
          self.templateHTML[name] = html;
        });

        $(this).remove();
      });
    },

    use: function (templateName, data) {
      var html = this.templateHTML[templateName];
      if (!isObject(data)) return this.error;
      for (var key in data) {
        var value = data[key];
        var re = new RegExp('{' + key + '}', 'g');
        html = html.replace(re, value);
      }
      return html;
    }
  };

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
   * Calls function 'onTab' when Tab is pressed on $(selector).
   */
  function callOnTab(selector, onTab) {
    $(selector).off('keydown').keydown(function (e) {
      var keyCode = e.keyCode || e.which;

      if (keyCode == 9) {
        e.preventDefault();
        onTab(this);
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

  function isArray(arr) {
    return Object.prototype.toString.call(arr) === '[object Array]';
  }
  function isObject(arr) {
    return Object.prototype.toString.call(arr) === '[object Object]';
  }
  function strToInt(str) {
    var i = parseInt(str);
    if (isNaN(i)) return 0;
    else return i;
  }
</script>