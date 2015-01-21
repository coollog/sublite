<input type="hidden" required name="<?php vecho('name'); ?>" value="<?php vecho(vget('name')); ?>" />
<subheadline><?php vecho('title'); ?></subheadline>
<div class="iframe"><iframe class="S3" src="S3.php?name='<?php vecho('name'); ?>'"></iframe></div>
<subheadline>Current Photo</subheadline>
<div class="img" name="<?php vecho('name'); ?>"><img src="<?php vecho(vget('name')); ?>" /></div>