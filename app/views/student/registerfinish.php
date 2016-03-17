<style>
  body {
    background-color: #ffd800;
  }
  confirmation {
    text-transform: uppercase;
    padding: 20px;
    font-size: 1.5em;
    line-height: 1.2em;
    display: block;
  }
</style>

<panel>
  <div class="content">
    <confirmation>A confirmation email has been sent to <strong><?php View::echof('email'); ?></strong>. Check your inbox or spam. The email may take up to 24 hours to show up.</confirmation>

    <?php View::partial('studentrefer', array('email' => View::get('email'))); ?>
  </div>
</panel>