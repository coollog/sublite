<input type="hidden" required name="<?php vecho('s3name'); ?>" value="<?php vecho(vget('s3name')); ?>" />
<subheadline><?php vecho('title'); ?></subheadline>
<div class="iframe"><iframe class="S3" src="S3.php?name='<?php vecho('s3name'); ?>'"></iframe></div>
<subheadline>Current Photo</subheadline>
<div class="img" name="<?php vecho('s3name'); ?>"><img src="<?php vecho(vget('s3name')); ?>" /></div>