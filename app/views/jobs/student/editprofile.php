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
    height: auto;
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
  section field {
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
  section field:hover {
    background: #daebf2;
    padding: 0.5em;
  }

  input.additem {
    background: #e8e8e8;
    font-weight: normal;
    margin-top: 1em;
    text-transform: none;
  }
</style>

<script>
  function flexInputResize(me) {
    $(me).height($(me)[0].scrollHeight);
    console.log($(me)[0].scrollHeight)
  }

  $(function() {
    // Set up flexinputs.
    $('textarea.flexinput')
      .attr('rows', 1)
      .keydown(function() {
        flexInputResize(this);
      });

  });
</script>

<panel class="profile">
  <div class="content">
    <headline>Profile for Job Applications</headline>
    <left>
      <student>
        <name>Amy Santos</name>
      </student>

      <basicinfo>
        <div>
          <label for="input-bio" class="fortextarea">Write a short summary for yourself:</label>
          <textarea id="input-bio" class="flexinput"></textarea>
          <input id="done-bio" type="button" value="Done" />
        </div>
      </basicinfo>

      <section name="education">
        <heading>Education</heading>
        <hr />
        <item>
          <h1><field name="school">Columbia University</field></h1>
          <h2>Class of <field name="school">2017</field></h2>
          <field name="degree"></field> in <dynamic name="majors"></dynamic>
          <br />
          <fieldline>
            <strong>Dates Attended: </strong>
              <field name="dates.start">August 2013</field>
              to
              <field name="dates.end">Present</field>
          </fieldline>
          <fieldline>
            <strong>Majors: </strong>
              <fields name="majors">
                <field name="majors">Computer Science</field>
                <field name="majors">Mathematics</field>
              </fields>
          </fieldline>
          <fieldline>
            <strong>Minors: </strong>
              <fields name="minors">
                <field name="minors">Blah</field>
              </fields>
          </fieldline>
          <fieldline>
            <strong>GPA: </strong>
              <field name="gpa"></field>
          </fieldline>
          <fieldline>
            <strong>Courses: </strong>
              <fields name="languages">
                <field name="languages">Databases</field>
                <field name="languages">Internet Security</field>
              </fields>
          </fieldline>
        </item>
        <input class="additem" type="button" value="Add Education" />
      </section>
    </left>
  </div>
</panel>