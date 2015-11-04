<script>
  $(function () {
    (function setupReport() {
      $('#reportbutton').off('click').click(function() {
        var report = confirm(
          'Are you sure you wish to report this application?' +
          '\nThe application will be disabled when reported.');
        if (!report) return;

        var data = { _id: '<?php View::echof('applicationId'); ?>' };
        $.post('report', data, function (data) {
          console.log(data);
          data = JSON.parse(data);
          $('reported').show();
        });
      });
    })();
  });
</script>

<?php if (View::get('isRecruiter')) { ?>
  <panel>
    <div class="content">
      <div>
        <i>
          Is this application spam or incomplete?<br />
          Reported applications will be reviewed and Credits will be refunded
          accordingly.
        </i>
      </div>
      <reported class="div hide green">
        The application has been reported for moderation.
        We will notify you and refund appropriate <i>Credits</i> when approved.
      </reported>
      <br />
      <input id="reportbutton" class="smallbutton" type="button"
             value="Report Application" />
    </div>
  </panel>
<?php } ?>