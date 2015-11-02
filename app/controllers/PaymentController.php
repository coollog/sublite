<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class PaymentController extends Controller {
    protected $billing;

    function __construct(BillingProvider $billing) {
      $this->billing = $billing;
    }

    function payNew() {
      //params: # of apps recruiter buys, id of recruiter, credit card information
      global $params, $MRecruiter;

      $stripeToken = $params['stripeToken'];
      $email = $params['email'];
      $numApps = $params['numApps'];
      $amount = calculateTotal($numApps);
      $data = array(
        'stripeToken' => $stripeToken,
        'amount' => $amount,
        'email' => $email,
        'currency' => 'usd'); //currency hardcoded

      //Information should already be validated with javascript, process the payment
      $results = $billing->chargeNew($data); //Charge the customer

      // TODO: Check for success
      // TODO: Put in $results['customerId'] into database
    }

    function payExisting() {
      //params: # of apps recruiter buys, id of recruiter
      global $params, $MRecruiter;

      $numApps = $params['numApps'];
      $amount = calculateTotal($numApps);
      $customerId = $MRecruiter->getPaymentInfo($params['id']);

      $data = array(
        'customerId' => $customerId,
        'amount' => $amount,
        'currency' => 'usd');

      //Information should already be validated with javascript, process the payment

      $results = $billing->chargeExisting($data); //Charge the customer

      // TODO: Check for success
      // Eric's error handling code below:
      // if ($results['success']) {
      //   $this->render('successfulpayment');
      // }
      // else {
      //   $this->render('failedpayment');
      // }
    }

    private function calculateTotal($numApps) {
      // TODO: Implement
    }
  }

  $CPayment = new PaymentController(new StripeBilling());
?>