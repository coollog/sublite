<panel>
  <div class="content" id="content">
  </div>
</panel>
<script src="<?php echo $GLOBALS["dirpre"]; ?>../public/vendor.js"></script>
<script src="<?php echo $GLOBALS["dirpre"]; ?>../public/app.js"></script>

<script>
  var module = require('views/jobs/portal/portal.js');
  var portal = new module.JobPortal();
  portal.render();
</script>