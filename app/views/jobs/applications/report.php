<style>
  reported {
    display: block;
    margin-bottom: 1em;
  }
</style>

<script>
  $(function () {
    (function setupReport() {
      $('#reportbutton').off('click').click(function() {
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
      <reported class="hide">
        The application has been reported for moderation.
        We will notify you and refund appropriate <i>Credits</i> when approved.
      </reported>
      <input id="reportbutton" type="button" value="Report Application" />
    </div>
  </panel>
<?php } ?>