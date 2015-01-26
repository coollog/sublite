<?php if (isset($search)) { ?>
	<br />Building Type?
<?php } else { ?>
	<h5>Building Type</h5>
<?php } ?>
<select name="building">
	<?php if (isset($search)) { ?>
		<option value="Any">Any</option>
	<?php } ?>
	<option value="Apartment Building">Apartment Building</option>
	<option value="Student Housing (Dorm)">Student Housing (Dorm)</option>
	<option value="Elevator Building">Elevator Building</option>
	<option value="Walk-Up">Walk-Up</option>
	<option value="Town-House">Town-House</option>
	<option value="Brownstone">Brownstone</option>
	<option value="Rowhouse">Rowhouse</option>
	<option value="Duplex">Duplex</option>
	<option value="House">House</option>
	<option value="Other">Other</option>
</select>