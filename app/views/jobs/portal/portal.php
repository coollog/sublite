<panel>
  <div class="content">
    Hello world!
  </div>
</panel>
<script src="<?php echo $GLOBALS["dirpre"]; ?>../public/vendor.js"></script>
<script src="<?php echo $GLOBALS["dirpre"]; ?>../public/app.js"></script>

<script>
  var module = require('views/jobs/portal/portal.js');
  module.JobPortal.populate();
</script>
