<div class="form-slider"><label>Amenities:</label></div>
<left class="checkboxes">
  <?php
    $amenities = array(
      "In-Building Gym",
      "Free Parking",
      "Reserved Parking (Additional Cost)",
      "Pool",
      "Rooftop Access",
      "Yard",
      "In-Building Mailboxes",
      "Laundry Machines",
      "Wi-Fi",
      "Cable",
      "Wheelchair Accessibility",
      "Sports Fields"
    );
    $i = 1;
    foreach ($amenities as $a) {
  ?>
      <input type="checkbox" name="amenities[]" id="amenities<?php echo $i; ?>" value="<?php echo $a; ?>" <?php View::checked('amenities', $a); ?> />
      <label style="display: inline-block;" for="amenities<?php echo $i; ?>"><?php echo $a; ?></label><br />
  <?php
      $i ++;
    }
  ?>
</left>