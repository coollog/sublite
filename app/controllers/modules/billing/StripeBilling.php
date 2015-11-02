<?php
require_once('BillingProvider.php');

class StripeBilling implements BillingProvider {

  //Stripe Billing implementation

  // email, token, amount, currency: "usd"
  public static function chargeNew($creditInfo) {
    //create new customer for first time payment

    $results = array();

    try {
      $customer = \Stripe\Customer::create(array(
        'email' => $creditInfo['email'],
        'card' => $creditInfo['token']
      ));

      $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount' => $creditInfo['amount'],
        'currency' => $creditInfo['currency']
      ));

      $results['customerId'] = $customer->id; //add customerId to results so it can be saved to database
      $results['success'] = true;

    } catch(\Stripe\Error\Card $e) {
      //The User's card has been declined
      $results['success'] = false;
    }

    return $results;
  }

  public static function chargeExisting($creditInfo) {

    $results = array();

    try {
      $charge = \Stripe\Charge::create(array(
        'customer' => $creditInfo['customerId'],
        'amount' => $creditInfo['amount'],
        'currency' => $creditInfo['currency']
      ));

      $results['success'] = true;

    } catch (\Stripe\Error\Card $e) {
      //The User's card has been declined
      $results['success'] = false;
    }

    return $results;
  }
}
?>