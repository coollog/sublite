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

  input.additem {
    background: #e8e8e8;
    font-weight: normal;
    margin-top: 1em;
    text-transform: none;
  }

  #savefail, #savesuccess {
    display: none;
  }
</style>
<?php View::partial('jobs/student/profilecommon'); ?>

<templates>
  <fieldinputtemplate>
    <input type="text" class="smallinput" value="{value}" parent="{parent}" {required} />
  </fieldinputtemplate>
  <fieldtextareatemplate>
    <textarea class="smallinput" parent="{parent}" {required}>{value}</textarea>
  </fieldtextareatemplate>

  <fieldtemplate>
    <field name="{name}" {required}>{val}</field>
  </fieldtemplate>

  <educationitemtemplate>
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
  <experienceitemtemplate>
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
  <extracurricularsitemtemplate>
    <item>
      <h1><field name="title" required {invalid}>{title}</field></h1>
      <h2><field name="organization" required {invalid}>{organization}</field></h2>
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
  <awardsitemtemplate>
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
  <projectsitemtemplate>
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

<textarea id="profileData" class="hide"><?php View::echof('profile'); ?></textarea>

<script>
  $(function() {
    var profileData = $('#profileData').html();
    var profile = JSON.parse(profileData);
    setupProfile(profile);

    setupFlexInput();
    setupAddItem();
    setupDeleteItem();
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
      organization: '[Organization Name]',
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
          $('#savefail, #savesuccess').hide();
        }

        fields.trigger('changed');

        parent.show();

        $(self).remove();
      }

      (function setupTabbing() {
        var index = 0;
        $('section field').each(function() {
          $(this).attr('index', index);
          index ++;
        });
        function nextField(me) {
          var parentId = $(me).attr('parent');
          var $parent = $('#' + parentId);
          var index = parseInt($parent.attr('index')) + 1;
          finish(me);
          $('section field[index='+index+']').click();
        }
        callOnTab('section .smallinput', nextField);
      })();

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
      $('#savefail, #savesuccess').hide();
    });
  }

  function setupFinish() {
    // Process the fields and get the JSON for the profile.
    function getProfile() {
      if ($('section field.invalid').size() > 0) {
        return null;
      }

      function getFields(sectionName, fieldName) {
        var list = [];
        $('section[name='+sectionName+'] field[name='+fieldName+']').each(function() {
          var val = $(this).html();
          list.push(val);
        });
        return list;
      }

      var data = {};

      // Get the basic info first.
      var bio = $('#input-bio').val();
      var resume = $('#resumelink').html();
      var interests = getFields('basicinfo', 'interests');
      var skills = getFields('basicinfo', 'skills');
      data.bio = bio;
      data.resume = resume;
      data.interests = interests;
      data.skills = skills;

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
        $('#savesuccess').show();
      });
    }

    $('#save').click(function () {
      var profile = getProfile();
      if (profile == null) {
        $('#savefail').show();
        return;
      }
      saveProfile(profile);
      window.onbeforeunload = null;
    });
  }

  function setupProfile(profile) {
    function addField(sectionName, fieldName, val, def) {
      function requiredDefault(val, def) {
        if (val) {
          return val;
        }
        return def;
      }

      var html = requiredDefault(val, def);
      $('section[name='+sectionName+'] field[name='+fieldName+']').each(function() {
        $(this).html(html);
        if (html === def) {
          $(this).addClass('invalid');
        }
      });
    }
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
    var resume = profile.resume;
    var interests = profile.interests;
    var skills = profile.skills;

    $('section[name=student] heading').html(name);
    $('#input-bio').val(bio);
    updateResumeLink(resume);
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
  function updateResumeLink(resume) {
    if (!resume) {
      $('section[name=basicinfo] resume').hide();
      return;
    }
    $('section[name=basicinfo] resume').each(function () {
      $(this).show();
      $(this).children('a').attr('href', resume);
    });
    $('#resumelink').html(resume);
  }
</script>

<panel class="profile">
  <div class="content">
    <headline>Profile for Job Applications</headline>
    <left>
      <section name="student">
        <heading></heading>
      </section>

      <section name="basicinfo">
        <heading></heading>

        <label for="input-bio" class="fortextarea">
          Write a short summary for yourself:
        </label>
        <textarea id="input-bio" class="flexinput"></textarea>

        <fieldline>
          <strong>Resume: </strong>
          <resume class="hide">
            <a target="_blank">
              View Existing Resume
            </a>
          </resume>
        </fieldline>
        <?php View::partial('S3/resume'); ?>
        <div class="hide" id="resumelink"></div>

        <fieldline>
          <strong>Interests: </strong>
          <fields name="interests"></fields>
          <addfield name="interests"></addfield>
        </fieldline>

        <fieldline>
          <strong>Skills: </strong>
          <fields name="skills"></fields>
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
      <green id="savesuccess">Profile saved!</green>
      <br />
      <input id="save" type="button" value="Save" />
    </div>
  </div>
</panel>

<script>
  formunloadjobsmsg("Are you sure you wish to leave this page? Unsaved changes will be lost.");
</script>