<style>
  .applicationblock {
    text-align: left;
    padding: 0.5em 0;
    border-bottom: 1px solid #ddd;
    color: #000;
    width: 100%;
  }
  .applicationblock .title {
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
  .applicationblock .title:hover {
    opacity: 0.5;
  }
  .applicationblock applicationtitle {
    font-size: 1.5em;
    color: #03596a;
    line-height: 40px;
  }
  .applicationblock location {
    opacity: 0.5;
  }
  .applicationblock .buttons {
    text-align: right;
  }
</style>

<templates>
  <applicationtemplate>
    <table class="applicationblock"><tr>
      <td class="title">
        <a href="job?id={jobId}" target="_blank">
          <applicationtitle>{company}|{title}</applicationtitle><br />
          <location>{location}</location>
        </a>
      </td>
      <td class="buttons">
        <a href="apply/{jobId}">
          <input type="button" value="{vieworedit} Application" />
        </a>
      </td>
    </tr></table>
  </applicationtemplate>
  <noapplicationstemplate>
    <b style="font-size: 1.5em;">
      You haven't {savedorsubmitted} any applications yet!
    </b>
  </noapplicationstemplate>
</templates>

<applicationData class="hide">
  <?php
    $applications = View::get('applications');
    echo json_encode($applications);
  ?>
</applicationData>

<script>
  $(function () {
    (function setupApplications() {
      var applicationData = JSON.parse($('applicationData').html());
      var numSaved = 0;
      var numSubmitted = 0;
      applicationData.forEach(function (application) {
        var jobId = application.jobId.$id;
        var title = application.title;
        var location = application.location;
        var vieworedit = application.submitted ? "View" : "Edit";

        var data = {
          jobId: jobId,
          title: title,
          location: location,
          vieworedit : vieworedit
        }
        var applicationHTML = useTemplate('applicationtemplate', data);
        if (application.submitted) {
          $('submittedapplications').append(applicationHTML);
          numSubmitted++;
        } else {
          $('savedapplications').append(applicationHTML);
          numSaved++;
        }
      });
      if (numSaved == 0) {
        var data = {
          savedorsubmitted : "saved"
        }
        var noApplicationsHTML = useTemplate('noapplicationstemplate', data);
        $('savedapplications').html(noApplicationsHTML);
      }
      if (numSubmitted == 0) {
        var data = {
          savedorsubmitted : "submitted"
        }
        var noApplicationsHTML = useTemplate('noapplicationstemplate', data);
        $('submittedapplications').html(noApplicationsHTML);
      }
    })();
  });
</script>

<panel class="applications">
  <div class="content">
    <headline>Saved Applications</headline>
    <savedapplications></savedapplications>
    <headline style="margin-top: 100px;">Submitted Applications</headline>
    <submittedapplications></submittedapplications>
  </div>
</panel>