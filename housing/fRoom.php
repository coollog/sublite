<?php if (isset($search)) { ?>
	<br />Room Type?
<?php } else { ?>
	<h5>Room Type</h5>
<?php } ?>
<select name="room">
	<?php if (isset($search)) { ?>
		<option value="Any">Any</option>
	<?php } ?>
	<option value="Private Room">Private Room</option>
	<option value="Shared Room">Shared Room</option>
	<option value="Entire Home/Apt">Entire Home/Apt</option>
	<option value="Other">Other</option>
</select>