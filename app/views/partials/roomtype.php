<?php
  $roomtypes = array(
    "Private Room",
    "Shared Room",
    "Entire Home/Apt",
    "Other"
  );
  if (!is_null(vget('any')))
    array_unshift($roomtypes, 'Any');
?>

<div class="form-slider"><label for="roomtype">Room type: </label>
<select id="roomtype" name="roomtype" required>
  <?php
    vecho('roomtype', '<option selected="selected">{var}</option>');
    foreach ($roomtypes as $roomtype) {
      echo "<option>$roomtype</option>";
    }
  ?>
</select></div>