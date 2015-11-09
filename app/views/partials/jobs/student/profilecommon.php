<style>
  section {
    display: block;
    line-height: 1.5em;
  }
  section heading {
    font-size: 2em;
    line-height: 1.5em;
    font-weight: bold;
    margin-top: 1.5em;
    display: block;
  }
  section item {
    display: block;
    margin: 2em 0em;
    position: relative;
  }
  section item delete {
    position: absolute;
    background:
      url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/x.png')
      no-repeat center center;
    background-size: contain;
    width: 2em;
    height: 2em;
    display: block;
    opacity: 0.2;
    cursor: pointer;
    transition: 0.1s all ease-in-out;
    right: 0;
    top: 0;
  }
  section item delete:hover {
    opacity: 1;
  }
  section item h1 {
    font-size: 1.5em;
    font-weight: normal;
    line-height: 1.5em;
    margin: 0;
  }
  section item h2 {
    font-size: 1.3em;
    font-weight: normal;
    line-height: 1.5em;
    margin: 0;
  }
  section fieldline {
    margin: 0.5em 0;
    display: block;
  }
  section field, addfield {
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
  section field.invalid {
    border-bottom: 2px ridge red;
  }
  section field:not(.nohover):hover {
    background: #daebf2;
    padding: 0.5em;
    border: 0;
  }
  section fields field:before {
    content: ', ';
  }
  section fields field:first-child:before {
    content: none;
  }
  section addfield {
    opacity: 0.3;
    width: 4em;
    height: 1.5em;
    margin-bottom: -0.5em;
    margin-left: 0.5em;
    background:
      url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/plus_black.png')
      no-repeat center center;
    background-size: contain;
    display: inline-block;
    border: dotted 1px #000;
  }
  section addfield:hover {
    opacity: 1;
    background-color: #daebf2;
  }
</style>

<script>
  var Template = {
    makeField: function (fieldName, val, isRequired) {
      if (isRequired) {
        var required = 'required';
      } else {
        var required = '';
      }
      var data = {
        name: fieldName,
        val: val,
        required: required
      };
      var fieldHTML = useTemplate('fieldtemplate', data);

      return fieldHTML;
    },
    makeItem: function (sectionName, data, isInvalid) {
      if (isInvalid) {
        data.invalid = 'class="invalid"';
      } else {
        data.invalid = '';
      }
      for (var fieldName in data) {
        var field = data[fieldName];
        if (isObject(field)) {
          for (var key in field) {
            data[fieldName+'.'+key] = field[key];
          }
        }
      }

      var templateName = sectionName + 'itemtemplate';
      var itemHTML = useTemplate(templateName, data);

      // Now add field sets.
      $('body').append(
        '<div id="_makeItemTemp" class="hide">'+itemHTML+'</div>'
      );

      for (var fieldName in data) {
        var field = data[fieldName];
        if (isArray(field)) {
          field.forEach(function(val) {
            var fieldHTML = Template.makeField(fieldName, val);
            $('#_makeItemTemp fields[name='+fieldName+']').append(fieldHTML);
          });
        }
      }

      itemHTML = $('#_makeItemTemp').html();
      $('#_makeItemTemp').remove();

      return itemHTML;
    }
  };
</script>