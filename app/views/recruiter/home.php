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
      width: 100% !important;
    }
  }
  fullblock {
    display: block;
    width: 100%;
  }
  halfblock {
    display: inline-block;
    width: 49%;
  }
  block {
    background: white;
    display: block;
    margin: 0.5em;
    padding: 2em;
    box-sizing: border-box;
  }
  block subheadline {
    font-size: 1.5em;
    line-height: 1.5em;
    margin-bottom: 1em;
    text-transform: uppercase;
  }
  block right {
    margin-top: 1em;
    font-size: 0.8em;
  }

  #bg-left {
    width: 30%;
  }
  #bg-right {
    width: 69%;
  }

  .roundpic {
    background: transparent no-repeat center center;
    background-size: cover;
    border-radius: 50px;
    display: block;
    width: 100px;
    height: 100px;
    margin: 1em auto;
  }

  #b-personal {
  }
  #b-personal profpic {
    background-image: url('<?php View::echoArray('personal', 'photo'); ?>');
  }
  #b-personal name {
    display: block;
    font-weight: bold;
    font-size: 1.1em;
  }
  #b-personal position, #b-personal company {
    display: block;
  }
  #b-company company, #b-company location {
    display: block;
  }
  #b-company companypic {
    background-image: url('<?php View::echoArray('company', 'logophoto'); ?>');
  }
  #b-graph block {
  }
  #b-views {
  }
  #b-stats {
  }
  #b-messages .message {
    width: 100%;
    padding: 0.5em 0;
  }
  #b-messages .message td {
    vertical-align: middle;
    color: black;
    text-align: left;
  }
  #b-messages .message .pic {
    width: 80px;
  }
  #b-messages .message .pic div {
    background: transparent no-repeat center center;
    background-size: cover;
    width: 60px;
    height: 60px;
    border-radius: 30px;
  }
  #b-messages .message .body time {
    opacity: 0.5;
    display: block;
  }

  btitle {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 1.2em;
    letter-spacing: 0.5px;
    display: block;
    line-height: 1.5em;
  }
  btitle .bigger {
    font-size: 1.4em;
  }
  bignum {
    color: #035e75;
    font-size: 72px;
    line-height: 1em;
  }

  joblistings {
    display: block;
    margin: 0em 5em;
  }
  joblisting {
    margin: 1em 0;
  }
  joblisting, info, options {
    display: block;
    text-align: left;
  }
</style>

<jobData class="hide"><?php View::echof('jobs'); ?></jobData>
<messageData class="hide"><?php View::echof('messages'); ?></messagedata>

<templates>
  <joblistingtemplate>
    <joblisting>
      <info>
        <a href="../jobs/job?id={_id}" target="_blank">
          {title} | {location}
        </a>
      </info>
      <buttons>
        <a href="viewapplicants/{_id}">{applicants} applicants</a> |
        <a href="editjob?id={_id}">Edit Job</a> |
        <a href="editapplication/{_id}">Edit Application</a> |
        <a href="deletejob/{_id}" class="red">Delete Job</a>
      </buttons>
    </joblisting>
  </joblistingtemplate>
  <messagetemplate>
    <a href="<?php echo $GLOBALS['dirpre']; ?>../employers/messages?id={_id}">
      <table class="message hover"><tr>
        <td class="pic">
          <div style="background-image: url('{frompic}');"></div>
        </td>
        <td class="body">
          <time>{time}</time>
          <msg>{msg}</msg>
        </td>
      </tr></table>
    </a>
  </messagetemplate>
</templates>

<script>
  $(function () {
    Templates.init();

    (function setupJobListings() {
      var jobData = $.parseJSON($('jobData').html());
      if (jobData.length == 0) return;
      $('joblistings').html('');
      jobData.forEach(function (job) {
        var jobListing = Templates.use('joblistingtemplate', job);
        $('joblistings').append(jobListing);
      });
    })();

    (function setupMessages() {
      var messageData = $.parseJSON($('messageData').html());
      if (messageData.messages.length == 0) return;
      $('inbox').html('');
      messageData.messages.forEach(function (m) {
        var message = Templates.use('messagetemplate', m);
        $('inbox').append(message);
      });
    })();
  });
</script>

<panel id="dashboard">
  <div class="content">
    <headline>Dashboard</headline>

    <fullblockgroup id="bg-left">
      <fullblock id="b-personal">
        <block>
          <btitle>Personal Profile</btitle>

          <a href="<?php echo $GLOBALS['dirpre']; ?>../employers/recruiter?id=<?php View::echof('id')?>">
            <profpic class="roundpic"></profpic>
          </a>

          <name>
            <?php View::echoArray('personal', 'firstname'); ?>
            <?php View::echoArray('personal', 'lastname'); ?>
          </name>
          <position><?php View::echoArray('personal', 'title'); ?></position>
          <company><?php View::echoArray('company', 'name'); ?></company>

          <a href="editprofile"><right>edit profile</right></a>
        </block>
      </fullblock>
      <fullblock id="b-company">
        <block>
          <btitle>Company Profile</btitle>

          <companypic class="roundpic"></companypic>

          <company><?php View::echoArray('company', 'name'); ?></company>
          <location><?php View::echoArray('company', 'location'); ?></location>

          <strong>
            <jobcount>
              <?php View::echoArray('company', 'jobcount'); ?>
            </jobcount> Job Listings<br />
            <applicantcount>
              <?php View::echoArray('company', 'applicantcount'); ?>
            </applicantcount> Applicants
          </strong>

          <a href="editcompany"><right>edit company</right></a>
        </block>
      </fullblock>
    </fullblockgroup>
    <fullblockgroup id="bg-right">
      <!-- <fullblock id="b-graph">
        <block></block>
      </fullblock> -->
      <fullblock id="b-stats">
        <halfblock id="b-views">
          <block>
            <bignum><?php View::echoArray('stats', 'views'); ?></bignum>
            <btitle class="bigger">Listing Views</btitle>
          </block>
        </halfblock>
        <halfblock id="b-saved">
          <block>
            <bignum><?php View::echoArray('stats', 'clicks'); ?></bignum>
            <btitle class="bigger">Clicks on Apply</btitle>
          </block>
        </halfblock>
      </fullblock>
      <fullblock id="b-messages">
        <block>
          <subheadline>Message Inbox</subheadline>
          <inbox>
            You have no messages so far.
          </inbox>

          <a href="messages"><right>Go to Inbox</right></a>
        </block>
      </fullblock>
    </fullblockgroup>
    <fullblock id="b-listings">
      <block>
        <subheadline>Job Listings</subheadline>
        <joblistings>
          You have no job listings. Start recruiting by creating your first job listing!
        </joblistings>

        <a href="addjob"><right>List a Job</right></a>
      </block>
    </fullblock>
  </div>
</panel>