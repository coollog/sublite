<?php
interface BillingProvider {
  public static function chargeNew($creditInfo);
  public static function chargeExisting($creditInfo);
}
?>
