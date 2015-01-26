<optionbox>
	<obtitle>
		<?php
			if (!isset($aTitle)) {
				$aTitle = 'Choose Amenities';
			}
			echo $aTitle;
		?>
	</obtitle>
	<oboptions>
		<?php
			$amenities = array(
				"gym" => "In-Building Gym",
				"freeparking" => "Free Parking",
				"reservedparking" => "Reserved Parking (Additional Cost)",
				"pool" => "Pool",
				"rooftop" => "Rooftop Access",
				"yard" => "Yard",
				"mailboxes" => "In-Building Mailboxes",
				"laundry" => "Laundry Machines",
				"wifi" => "Wi-Fi",
				"cable" => "Cable",
				"wheelchair" => "Wheelchair Accessibility",
				"sports" => "Sports Fields"
			);
			foreach ($amenities as $amenity => $text) {
		?>
			<div><input type="checkbox" name="amenities[]" value="<?php echo $text; ?>" id="a<?php echo $amenity; ?>"><label for="a<?php echo $amenity; ?>"><?php echo $text; ?></label></div>
		<?php
			}
		?>
	</oboptions>
</optionbox>