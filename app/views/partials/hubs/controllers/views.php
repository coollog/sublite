<script>
  // Controller for the views templates

  var Views = {
    templates: {},
    setup: function () {
      var templates = this.templates;
      $('viewtemplate').each(function() {
        var name = $(this).attr('name');
        templates[name] = $(this).html();
        $(this).remove();
      });
    },
    // Changes the view with json to replace {var} and back=true meaning
    //  slide view from left instead
    render: function (name, json, back, callback) {
      function finishRender(newHTML) {
        $('view').html(newHTML);

        // Process json dynamics
        if (json.ismember) $('#joinpanel').remove();
        thishubname = json.name;

        if (json.isgoing) {
          $('.goingornot').hide();
          $('#leavemeetupdiv').show();
        }

        if (!json.iscreator) $('tab[for=edit]').remove();

        afterRender();
        if (typeof callback !== 'undefined') callback();
      }

      var newHTML = this.templates[name];
      for (var key in json) {
        toreplace = '{'+key+'}';
        while (newHTML.indexOf(toreplace) > -1)
          newHTML = newHTML.replace(toreplace, json[key]);
      }

      var oldHTML = $('view').html();
      if (oldHTML.length) {
        var viewPos = {
          viewStart: '0%',
          viewEnd: '-100%',
          newviewStart: '100%',
          newviewEnd: '0%'
        };
        if (back) {
          viewPos = {
            viewstart: '0%',
            viewEnd: '100%',
            newviewStart: '-100%',
            newviewEnd: '0%'
          };
        }
        $('view').css('position', 'absolute')
                 .css('left', viewPos.viewStart)
                 .animate({ left: viewPos.viewEnd },
                 500, 'easeOutCubic');
        $('newview').css('left', viewPos.newviewStart).html(newHTML)
                    .animate({ left: viewPos.newviewEnd }, 
                    500, 'easeOutCubic', function() {
          $('newview').html('');
          $('view').css('position', 'relative').css('left', '0');
          finishRender(newHTML, json);
        });
      } else {
        finishRender(newHTML, json);
      }
    }
  };
</script>