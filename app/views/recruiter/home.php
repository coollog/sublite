<panel>
  <div class="content">
    <?php
      $id = vget('_id');
      $company = vget('company');
      echo vlinkto('<input type="button" value="Edit Profile" />', 'editprofile');
      echo vlinkto('<input type="button" value="Edit Company Profile" />', 'editcompany');
    ?>
  </div>
</panel>


<style>
  #dashboard {
    background: #fbfaf6;

  }

  fullblockgroup {
    display: inline-block;
    vertical-align: top;
  }
  @media (max-width: 1000px) {
    fullblockgroup {
      display: block;
      width: 100%;
    }
  }
  fullblock {
    display: block;
    width: 100%;
  }
  halfblock {
    display: inline-block;
    width: 50%;
  }
  block {
    background: white;
    display: block;
    margin: 1em;
    padding: 1em;
  }

  #bg-left {
    width: 30%;
  }
  #bg-right {
    width: 69%;
  }

  #b-personal {
    height: 200px;
  }
  #b-company {
    height: 200px;
  }
  #b-graph {
    height: 150px;
  }
  #b-views {
    height: 50px;
  }
  #b-stats {
    height: 50px;
  }
  #b-messages {
    height: 200px;
  }

  btitle {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 1.2em;
    letter-spacing: 0.5px;
    display: block;
  }
</style>

<panel id="dashboard">
  <div class="content">
    <headline>Dashboard</headline>

    <fullblockgroup id="bg-left">
      <fullblock id="b-personal">
        <block>
          <btitle>Personal Profile</btitle>

          <profpic></profpic>

          <name>Mary Smith</name>
          <position>Senior Coordinator</position>
          <company>Point</company>

          <strong>Looking for:</strong>
          <jobnames>Web Developer, Graphic Designer, Accountant</jobnames>
        </block>
      </fullblock>
      <fullblock id="b-company">
        <block>
          <btitle>Personal Profile</btitle>

          <companypic></companypic>

          <company>Point</company>
          <location>San Francisco, California</location>

          <strong>
            <jobcount>64</jobcount> Job Listings<br />
            <applicantcount>3139</applicantcount> Applicants
          </strong>
        </block>
      </fullblock>
    </fullblockgroup>
    <fullblockgroup id="bg-right">
      <fullblock id="b-graph">

      </fullblock>
      <fullblock id="b-stats">
        <halfblock id="b-views">

        </halfblock>
        <halfblock id="b-saved">

        </halfblock>
      </fullblock>
      <fullblock id="b-messages">

      </fullblock>
    </fullblockgroup>
    <fullblock id="b-listings">

    </fullblock>
  </div>
</panel>