<style>
  panel.profile {
    background: #fafafa;
  }
  .content {
    background: white;
    padding: 50px;
    margin: 50px;
    box-shadow: 1px 1px #ccc;
    margin: 0 auto;
  }

  textarea.flexinput {
    resize: none;
    overflow: hidden;
  }
  .flexinputhidden {
    display: none;
    white-space: pre-wrap;
    word-wrap: break-word;
    overflow-wrap: break-word; /* future version of deprecated 'word-wrap' */
  }
  /**
   * The styles for 'flexinputcommmon' are applied to both the textarea and the
   * hidden clone.
   * These must be the same for both.
   */
  .flexinputcommon {
    height: auto;
  }
  .flexinputbr {
    line-height: 3px;
  }

  section input.smallinput {
    width: 200px;
    margin: -0.75em 0 -0.5em 0;
  }

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
  section field:hover {
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
    width: 1.5em;
    height: 1.5em;
    margin-bottom: -0.5em;
    margin-left: 0.5em;
    background:
      url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/plus_black.png')
      no-repeat center center;
    background-size: contain;
    display: inline-block;
    border: dotted 1px #000;
    width: 4em;
  }
  section addfield:hover {
    opacity: 1;
    background-color: #daebf2;
  }

  input.additem {
    background: #e8e8e8;
    font-weight: normal;
    margin-top: 1em;
    text-transform: none;
  }

  #savefail {
    display: none;
  }
</style>

<templates>
  <fieldinputtemplate class="hide">
    <input type="text" class="smallinput" value="{value}" parent="{parent}" {required} />
  </fieldinputtemplate>
  <fieldtextareatemplate class="hide">
    <textarea class="smallinput" parent="{parent}" {required}>{value}</textarea>
  </fieldtextareatemplate>

  <fieldtemplate class="hide">
    <field name="{name}" {required}>{val}</field>
  </fieldtemplate>

  <educationitemtemplate class="hide">
    <item>
      <h1><field name="school" required {invalid}>{school}</field></h1>
      <h2>Class of <field name="class" required {invalid}>{class}</field></h2>
      <field name="degree" required {invalid}>{degree}</field>
      in
      <dynamic name="majors"></dynamic>
      <br />
      <fieldline>
        <strong>Dates Attended: </strong>
        <field name="dates.start" required {invalid}>{dates.start}</field>
        to
        <field name="dates.end" required {invalid}>{dates.end}</field>
      </fieldline>
      <fieldline>
        <strong>Majors: </strong>
        <fields name="majors"></fields>
        <addfield name="majors"></addfield>
      </fieldline>
      <fieldline>
        <strong>Minors: </strong>
        <fields name="minors"></fields>
        <addfield name="minors"></addfield>
      </fieldline>
      <fieldline>
        <strong>GPA: </strong>
        <field name="gpa" required {invalid}>{gpa}</field>
      </fieldline>
      <fieldline>
        <strong>Courses: </strong>
          <fields name="courses"></fields>
          <addfield name="courses"></addfield>
      </fieldline>
      <delete></delete>
    </item>
  </educationitemtemplate>
  <experienceitemtemplate class="hide">
    <item>
      <h1><field name="title" required {invalid}>{title}</field></h1>
      <h2><field name="company" required {invalid}>{company}</field></h2>
      <fade>
        <field name="dates.start" required {invalid}>{dates.start}</field>
        to
        <field name="dates.end" required {invalid}>{dates.end}</field>
        |
        <field name="location" required {invalid}>{location}</field>
      </fade>
      <br /><br />
      <field name="summary" type="textarea" required {invalid}>{summary}</field>
      <delete></delete>
    </item>
  </experienceitemtemplate>
  <extracurricularsitemtemplate class="hide">
    <item>
      <h1><field name="title" required {invalid}>{title}</field></h1>
      <h2><field name="organiation" required {invalid}>{organiation}</field></h2>
      <fade>
        <field name="dates.start" required {invalid}>{dates.start}</field>
        to
        <field name="dates.end" required {invalid}>{dates.end}</field>
        |
        <field name="location" required {invalid}>{location}</field>
      </fade>
      <br /><br />
      <field name="summary" type="textarea" required {invalid}>{summary}</field>
      <delete></delete>
    </item>
  </extracurricularsitemtemplate>
  <awardsitemtemplate class="hide">
    <item>
      <h1><field name="name" required {invalid}>{name}</field></h1>
      <fieldline>
        <strong>Awarded by: </strong>
        <field name="by" required {invalid}>{by}</field>
      </fieldline>
      <fade>
        <field name="date" required {invalid}>{date}</field>
        |
        <field name="location" required {invalid}>{location}</field>
      </fade>
      <br /><br />
      <field name="summary" type="textarea" required {invalid}>{summary}</field>
      <delete></delete>
    </item>
  </awardsitemtemplate>
  <projectsitemtemplate class="hide">
    <item>
      <h1><field name="name" required {invalid}>{name}</field></h1>
      <fade>
        <field name="dates.start" required {invalid}>{dates.start}</field>
        to
        <field name="dates.end" required {invalid}>{dates.end}</field>
      </fade>
      <br /><br />
      <fieldline>
        <strong>Links: </strong>
        <fields name="links"></fields>
        <addfield name="links"></addfield>
      </fieldline>
      <field name="summary" type="textarea" required {invalid}>{summary}</field>
      <delete></delete>
    </item>
  </projectsitemtemplate>
</templates>

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

      var templateName = sectionName + 'itemtemplate';
      var itemHTML = useTemplate(templateName, data);

      // Now add field sets.
      $('body').append(
        '<div id="_makeItemTemp" style="display:none;">'+itemHTML+'</div>'
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

  $(function() {
    // var profile = {
    //   interests: ['Tech', 'Finance', 'Consulting'],
    //   education: [{
    //     school: 'Yale University',
    //     class: '2017',
    //     degree: 'B.S.',
    //     'dates.start': 'August 2013',
    //     'dates.end': 'May 2017',
    //     majors: ['Computer Science', 'Mathematics'],
    //     minors: ['blah'],
    //     gpa: 2.5,
    //     courses: ['Horseriding', 'Neighing', 'Stomping']
    //   }]
    // };
    var profile = JSON.parse('<?php View::echof('profile'); ?>');
    setupProfile(profile);

    setupFlexInput();
    setupAddItem();
    setupFields();
    setupFinish();
  });

  var templateDefaults = {
    education: {
      school: '[School Name]',
      class: '[Year (eg. 2017)]',
      degree: '[Degree (eg. BS)]',
      'dates.start': '[Start (eg. August 2015)]',
      'dates.end': '[End (eg. May 2019)]',
      gpa: '[GPA (eg. 3.68)]'
    },
    experience: {
      title: '[Title (eg. Intern)]',
      company: '[Company Name]',
      'dates.start': '[Start Date (eg. August 2015)]',
      'dates.end': '[End Date (eg. May 2019)]',
      location: '[Location (eg. New York City)]',
      summary: '[Summary/Description]'
    },
    extracurriculars: {
      title: '[Title (eg. President)]',
      extracurriculars: '[Organization Name]',
      'dates.start': '[Start Date (eg. August 2015)]',
      'dates.end': '[End Date (eg. May 2019)]',
      location: '[Location (eg. New Haven)]',
      summary: '[Summary/Description]'
    },
    awards: {
      name: '[Award Name]',
      by: '[Organization/Competition]',
      date: '[Date (eg. May 2015)]',
      location: '[Location (eg. Boston)]',
      summary: '[Summary/Description]'
    },
    projects: {
      name: '[Project Name]',
      by: '[Organization/Competition]',
      'dates.start': '[Start Date (eg. August 2015)]',
      'dates.end': '[End Date (eg. October 2015)]',
      location: '[Location (eg. San Francisco)]',
      summary: '[Summary/Description]'
    }
  };
  function addItem() {
    var parent = $(this).parent('section');
    var sectionName = parent.attr('name');

    var data = templateDefaults[sectionName];
    var itemHTML = Template.makeItem(sectionName, data, true);

    parent.children('items').append(itemHTML);

    setupFields();
    setupDeleteItem();
  }

  function setupFlexInput() {
    var txt = $('textarea.flexinput');
    var hiddenDiv = $(document.createElement('div'));
    var content = null;
    var br = '<br class="flexinputbr">';

    txt.attr('rows', 1);
    txt.addClass('flexinputcommon');

    hiddenDiv.addClass('flexinputhidden flexinputcommon');
    $('body').append(hiddenDiv);

    txt.on('keydown', function () {
      content = $(this).val();

      content = content.replace(/\n/g, '<br>');
      hiddenDiv.html(content + br + br);

      $(this).css('height', hiddenDiv.height());
    });
  }

  function setupAddItem() {
    $('section .additem').off('click').click(addItem);
  }

  function setupFields() {
    function setupSmallInputs() {
      function finish(self) {
        var val = $(self).val();
        var required = $(self).prop('required');
        var parentId = $(self).attr('parent');
        var parent = $('#' + parentId);
        var fields = parent.parent('fields');

        if (val == '') {
          if (!required) {
            parent.remove();
          }
        } else {
          parent.html(val);
          parent.removeClass('invalid');
          $('#savefail').hide();
        }

        fields.trigger('changed');

        parent.show();

        $(self).remove();
      }
      callOnEnter('section .smallinput', finish);
      $('section .smallinput').blur(function () {
        finish(this);
      });
    }

    // Setup each 'field'.
    function setupField() {
      $('section field')
        .removeUniqueId().uniqueId()
        .off('click').click(function() {
          // Finish all other smallinputs.
          triggerEnter('section .smallinput');

          // Get my attributes.
          var myVal = $(this).html();
          var myId = $(this).attr('id');
          var isRequired = typeof $(this).attr('required') !== 'undefined';
          var isInvalid = $(this).hasClass('invalid');
          var myType = $(this).attr('type');

          // Set up data for smallinput.
          if (isInvalid) {
            myVal = '';
          }
          var data = {
            value: myVal,
            parent: myId,
            required: ''
          };
          if (isRequired) {
            data.required = 'required';
          }

          // Set up smallinput and hide field.
          if (myType == 'textarea') {
            var inputHTML = useTemplate('fieldtextareatemplate', data);
          } else {
            var inputHTML = useTemplate('fieldinputtemplate', data);
          }
          $(this).after(inputHTML).hide();
          $('section .smallinput[parent='+myId+']').focus();

          setupSmallInputs();
        });
    }

    // Setup each 'addfield'.
    function setupAddField() {
      $('section addfield')
        .off('click').click(function() {
          var fieldName = $(this).attr('name');

          var fieldHTML = Template.makeField(fieldName, '');

          var fields = $(this).parent().children('fields[name='+fieldName+']');
          fields.append(fieldHTML);
          setupField();
          fields.children('field').last().click();
        });
    }

    function setupDynamic() {
      $('section dynamic').each(function () {
        var fieldsName = $(this).attr('name');
        var dynamic = $(this);

        $('section fields[name='+fieldsName+']')
          .off('changed').on('changed', function () {
            var list = [];
            $(this).children('field').each(function () {
              var val = $(this).html();
              list.push(val);
            });

            dynamic.html(list.join(', '));
          });
      });

      $('section fields').trigger('changed');
    }

    setupField();
    setupAddField();
    setupDynamic();
  }

  function setupDeleteItem() {
    $('section delete').off('click').click(function() {
      $(this).parent().remove();
      $('#savefail').hide();
    });
  }

  function setupFinish() {
    // Process the fields and get the JSON for the profile.
    function getProfile() {
      if ($('section field.invalid').size() > 0) {
        return null;
      }

      var data = {};

      // Get the basic info first.
      var bio = $('#input-bio').val();
      var interests = [];
      $('section[name=basicinfo]').each(function() {
        $(this).find('field[name=interests]').each(function() {
          var interest = $(this).html();
          interests.push(interest);
        });
      });
      data.bio = bio;
      data.interests = interests;

      // Get each section.
      function getSection(sectionName) {
        var sectionData = [];

        $('section[name='+sectionName+'] item').each(function() {
          var itemData = {};

          // Get all single fields.
          $(this).find('field').each(function() {
            var underFields = $(this).parent('fields').size() == 1;
            if (!underFields) {
              var name = $(this).attr('name');
              var val = $(this).html();
              var splitName = name.split('.');
              if (splitName.length == 2) {
                if (!(splitName[0] in itemData)) {
                  itemData[splitName[0]] = {};
                }
                itemData[splitName[0]][splitName[1]] = val;
              } else {
                itemData[name] = val;
              }
            }
          });

          // Get all field sets.
          $(this).find('fields').each(function() {
            var fieldsData = [];
            var name = $(this).attr('name');
            $(this).children('field').each(function() {
              var val = $(this).html();
              fieldsData.push(val);
            });
            itemData[name] = fieldsData;
          });

          sectionData.push(itemData);
        });

        return sectionData;
      }
      data.education = getSection('education');
      data.experience = getSection('experience');
      data.extracurriculars = getSection('extracurriculars');
      data.awards = getSection('awards');
      data.projects = getSection('projects');

      return data;
    }

    // Save the profile.
    function saveProfile(profile) {
      $.post('', {profile: profile}, function (data) {
        console.log('saved!');
        console.log(data);
      });
    }

    $('#save').click(function () {
      var profile = getProfile();
      if (profile == null) {
        $('#savefail').show();
        return;
      }
      saveProfile(profile);
    });
  }

  function setupProfile(profile) {
    function addFields(sectionName, fieldName, list) {
      if (!list) return;

      var selector = 'section[name='+sectionName+'] fields[name='+fieldName+']';

      list.forEach(function (val) {
        var fieldHTML = Template.makeField(fieldName, val);
        $(selector).append(fieldHTML);
      });
    }
    function addItems(sectionName, items) {
      if (!items) return;

      var selector = 'section[name='+sectionName+'] items';
      var itemsHTML = '';

      items.forEach(function (item) {
        itemsHTML += Template.makeItem(sectionName, item);
      });

      $(selector).append(itemsHTML);
    }

    var name = profile.name;
    var bio = profile.bio;
    var interests = profile.interests;
    var skills = profile.skills;

    $('section[name=student] heading').html(name);
    $('#input-bio').html(name);
    addFields('basicinfo', 'interests', interests);
    addFields('basicinfo', 'skills', skills);

    var sections = [
      'education',
      'experience',
      'extracurriculars',
      'awards',
      'projects'
    ];
    sections.forEach(function (sectionName) {
      var section = profile[sectionName];
      addItems(sectionName, section);
    });
  }
</script>

<panel class="profile">
  <div class="content">
    <headline>Profile for Job Applications</headline>
    <left>
      <section name="student">
        <heading>Amy Santos</heading>
      </section>

      <section name="basicinfo">
        <heading></heading>

        <label for="input-bio" class="fortextarea">Write a short summary for yourself:</label>
        <textarea id="input-bio" class="flexinput"></textarea>

        <fieldline>
          <strong>Interests: </strong>
          <fields name="interests"></fields>
          <addfield name="interests"></addfield>
        </fieldline>

        <fieldline>
          <strong>Skills: </strong>
          <fields name="skills">
            <field name="skills">Java</field>
            <field name="skills">Python</field>
            <field name="skills">Marketing</field>
            <field name="skills">Entrepreneurship</field>
          </fields>
          <addfield name="skills"></addfield>
        </fieldline>
      </section name="basicinfo">

      <section name="education">
        <heading>Education</heading>
        <hr />
        <items></items>
        <input class="additem" type="button" value="Add Education" />
      </section>

      <section name="experience">
        <heading>Experience</heading>
        <hr />
        <items></items>
        <input class="additem" type="button" value="Add Experience" />
      </section>
      <section name="extracurriculars">
        <heading>Extracurriculars</heading>
        <hr />
        <items></items>
        <input class="additem" type="button" value="Add Extracurricular" />
      </section>
      <section name="awards">
        <heading>Awards</heading>
        <hr />
        <items></items>
        <input class="additem" type="button" value="Add Award" />
      </section>
      <section name="projects">
        <heading>Projects</heading>
        <hr />
        <items></items>
        <input class="additem" type="button" value="Add Project" />
      </section>
    </left>

    <div style="margin-top: 4em; line-height: 2em;">
      <red id="savefail">You must fix the invalid fields (underlined in red).</red>
      <br />
      <input id="save" type="button" value="Save" />
    </div>
  </div>
</panel>