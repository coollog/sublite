<tabtemplate for="meetup">
  <div class="meetup">
    <name>{name}</name>
    <table class="info">
      <tr>
        <td class="l" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/calendar.png');"></td>
        <td>
          <datetime>{datetime}</datetime>
        </td>
      </tr>
      <tr>
        <td class="l" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/place.png');"></td>
        <td>
          <place>{place}</place>
        </td>
      </tr>
    </table>
    <button class="smallbutton">More</button>
    <info>{going} Going &nbsp; &nbsp; {comments} Comments</info>
  </div>
</tabtemplate>