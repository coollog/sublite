<?php View::partial('jobs/student/profilecommon'); ?>

<style>
  section field {
    cursor: auto;
  }
</style>

<templates>
  <fieldtemplate>
    <field class="nohover" name="{name}">{val}</field>
  </fieldtemplate>

  <educationitemtemplate>
    <item>
      <h1><field name="school" class="nohover">{school}</field></h1>
      <h2>Class of <field name="class" class="nohover">{class}</field></h2>
      <field name="degree" class="nohover">{degree}</field>
      in
      <majors>
        <fields name="majors"></fields>
      </majors>
      <br />
      <fieldline>
        <strong>Dates Attended: </strong>
        <field name="dates.start" class="nohover">{dates.start}</field>
        to
        <field name="dates.end" class="nohover">{dates.end}</field>
      </fieldline>
      <fieldline>
        <strong>Majors: </strong>
        <fields name="majors"></fields>
      </fieldline>
      <fieldline>
        <strong>Minors: </strong>
        <fields name="minors"></fields>
      </fieldline>
      <fieldline>
        <strong>GPA: </strong>
        <field name="gpa" class="nohover">{gpa}</field>
      </fieldline>
      <fieldline>
        <strong>Courses: </strong>
          <fields name="courses"></fields>
      </fieldline>
    </item>
  </educationitemtemplate>
  <experienceitemtemplate>
    <item>
      <h1><field name="title" class="nohover">{title}</field></h1>
      <h2><field name="company" class="nohover">{company}</field></h2>
      <fade class="nohover">
        <field name="dates.start" class="nohover">{dates.start}</field>
        to
        <field name="dates.end" class="nohover">{dates.end}</field>
        |
        <field name="location" class="nohover">{location}</field>
      </fade>
      <br /><br />
      <field name="summary" type="textarea" class="nohover">{summary}</field>
    </item>
  </experienceitemtemplate>
  <extracurricularsitemtemplate>
    <item>
      <h1><field name="title" class="nohover">{title}</field></h1>
      <h2><field name="organization" class="nohover">{organization}</field></h2>
      <fade class="nohover">
        <field name="dates.start" class="nohover">{dates.start}</field>
        to
        <field name="dates.end" class="nohover">{dates.end}</field>
        |
        <field name="location" class="nohover">{location}</field>
      </fade>
      <br /><br />
      <field name="summary" type="textarea" class="nohover">{summary}</field>
    </item>
  </extracurricularsitemtemplate>
  <awardsitemtemplate>
    <item>
      <h1><field name="name" class="nohover">{name}</field></h1>
      <fieldline>
        <strong>Awarded by: </strong>
        <field name="by" class="nohover">{by}</field>
      </fieldline>
      <fade class="nohover">
        <field name="date" class="nohover">{date}</field>
        |
        <field name="location" class="nohover">{location}</field>
      </fade>
      <br /><br />
      <field name="summary" type="textarea" class="nohover">{summary}</field>
    </item>
  </awardsitemtemplate>
  <projectsitemtemplate>
    <item>
      <h1><field name="name" class="nohover">{name}</field></h1>
      <fade class="nohover">
        <field name="dates.start" class="nohover">{dates.start}</field>
        to
        <field name="dates.end" class="nohover">{dates.end}</field>
      </fade>
      <br /><br />
      <fieldline>
        <strong>Links: </strong>
        <fields name="links"></fields>
      </fieldline>
      <field name="summary" type="textarea" class="nohover">{summary}</field>
    </item>
  </projectsitemtemplate>
</templates>

<panel>
  <div class="content">
    <headline>Student Profile</headline>
    <left>
      <section name="student">
        <heading></heading>
      </section>
      <a id="resume" class="hide" target="_blank">
        <br />
        <input type="button" value="View Resume" />
      </a>
      <section name="basicinfo">
        <heading></heading>
        <fieldline>
          <strong>Summary: </strong>
          <field name="bio" class="nohover"></field>
        </fieldline>
        <fieldline>
          <strong>Resume: </strong>
          <field name="resume" class="nohover"></field>
        </fieldline>
        <fieldline>
          <strong>Interests: </strong>
          <fields name="interests"></fields>
        </fieldline>

        <fieldline>
          <strong>Skills: </strong>
          <fields name="skills"></fields>
        </fieldline>
      </section name="basicinfo">

      <section name="education" class="hide">
        <heading>Education</heading>
        <hr />
        <items></items>
      </section>

      <section name="experience" class="hide">
        <heading>Experience</heading>
        <hr />
        <items></items>
      </section>
      <section name="extracurriculars" class="hide">
        <heading>Extracurriculars</heading>
        <hr />
        <items></items>
      </section>
      <section name="awards" class="hide">
        <heading>Awards</heading>
        <hr />
        <items></items>
      </section>
      <section name="projects" class="hide">
        <heading>Projects</heading>
        <hr />
        <items></items>
      </section>
    </left>
  </div>
</panel>

<textarea id="profileData" class="hide"><?php View::echof('profile'); ?></textarea>

<script>
  $(function () {
    Templates.init();

    var profileData = JSON.parse($('#profileData').html());

    (function setupProfile(profile) {
      function addField(sectionName, fieldName, val) {
        $('section[name='+sectionName+'] field[name='+fieldName+']').each(function() {
          if (!val) {
            $(this).parent().remove();
            return;
          }

          $(this).html(val);
        });
      }
      function addFields(sectionName, fieldName, list) {
        if (!list) return;

        var selector = 'section[name='+sectionName+'] fields[name='+fieldName+']';

        if (list.length == 0) {
          $(selector).parent().remove();
          return;
        }

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
          $(selector).closest('section').show();
        });

        $(selector).append(itemsHTML);
      }

      var name = profile.name;
      var bio = profile.bio;
      var resume = profile.resume;
      var interests = profile.interests;
      var skills = profile.skills;

      $('section[name=student] heading').html(name);
      addField('basicinfo', 'bio', bio);
      addField('basicinfo', 'resume', resume);
      addFields('basicinfo', 'interests', interests);
      addFields('basicinfo', 'skills', skills);
      if (resume) {
        $('#resume').show().attr('href', resume);
      }

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
    })(profileData);
  });
</script>