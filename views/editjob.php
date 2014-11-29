<?php require_once('view.php'); ?>

<form method="post">
  <input type="text" name="title" placeholder="Job Title" value="<?php vecho('title'); ?>" />
  <input type="text" name="deadline" placeholder="Deadline" value="<?php vecho('deadline'); ?>" />
  <input type="text" name="duration" placeholder="Duration" value="<?php vecho('duration'); ?>" />
  <input type="text" name="desc" placeholder="Description" value="<?php vecho('desc'); ?>" />
  <input type="text" name="funfacts" placeholder="Fun Facts" value="<?php vecho('funfacts'); ?>" />
  <input type="text" name="photo" placeholder="Photo" value="<?php vecho('photo'); ?>" />
  <input type="text" name="location" placeholder="Location" value="<?php vecho('location'); ?>" />
  <input type="text" name="requirements" placeholder="Requirements" value="<?php vecho('requirements'); ?>" />
  <input type="submit" name="edit" value="Save Job" />
</form>