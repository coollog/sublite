<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/billing/StripeBilling.php');

  interface PaymentControllerAJAXInterface {
    public static function addPaymentInfo();
    public static function removePaymentInfo();
    public static function buyCredits();
    public static function buyPlan();
    public static function getCards();
  }

  class PaymentControllerAJAX extends Controller
                              implements PaymentControllerAJAXInterface {
    const BUYPLAN_DISCOUNT = 'promo2016';

    public static function addPaymentInfo() {
      RecruiterController::requireLogin();
      global $params;

      $recruiterId = $_SESSION['_id'];
      $customerId = RecruiterModel::getCustomerId($recruiterId);
      if (is_null($customerId)) {
        // Create new customer id.
        $email = $_SESSION['email'];
        $customerId = StripeBilling::createCustomer($email);
        RecruiterModel::setCustomerId($recruiterId, $customerId);
      }

      // Add card info.
      $tokenObj = $params['token'];
      $token = $tokenObj['id'];

      $card = StripeBilling::addCard($customerId, $token);

      echo toJSON($card);
    }

    public static function removePaymentInfo() {
      RecruiterController::requireLogin();
      global $params;

      $recruiterId = $_SESSION['_id'];
      $customerId = RecruiterModel::getCustomerId($recruiterId);
      if (is_null($customerId)) {
        return self::ajaxError();
      }

      // Remove card.
      $cardId = $params['cardId'];
      StripeBilling::removeCard($customerId, $cardId);

      return self::ajaxSuccess();
    }

    public static function buyCredits() {
      RecruiterController::requireLogin();
      global $params;

      $recruiterId = $_SESSION['_id'];
      $customerId = RecruiterModel::getCustomerId($recruiterId);

      $cardId = $params['cardId'];
      $credits = $params['credits'];
      if ($credits <= 0) return self::ajaxError();
      $filtered = $params['filtered'];
      $filterRequest = $params['filterRequest'];

      // How much do we charge per application?
      $amount = $credits * 800;
      if ($credits > 50) {
        $amount *= 5/8;
      }
      if ($filtered) {
        $amount += $credits * 500;
      }
      $filteredText = $filtered ? ' Filtered' : '';
      $description = "$credits$filteredText Credits";

      // Charge the card.
      $err = StripeBilling::charge($customerId, $cardId, $amount, $description);
      if (!is_null($err)) {
        return self::ajaxError($err);
      }

      $message = "_id: $recruiterId, filterRequest: $filterRequest";
      sendgmail(['tony.jiang@yale.edu', 'qingyang.chen@gmail.com'],
                "info@sublite.net",
                'Filter Bought',
                $message);

      // Add the credits.
      RecruiterModel::addCredits($recruiterId, $credits);

      return self::ajaxSuccess();
    }

    public static function buyPlan() {
      RecruiterController::requireLogin();
      global $params;

      $recruiterId = $_SESSION['_id'];
      $customerId = RecruiterModel::getCustomerId($recruiterId);

      $cardId = $params['cardId'];
      $type = $params['type'];
      $term = $params['term'];
      $discount = $params['discount'];
      if ($discount == self::BUYPLAN_DISCOUNT) {
        $discountType = 'discounted';
      } else {
        $discountType = 'normal';
      }

      $costs = [
        'basic' => [
          '1' => ['normal' => 39, 'discounted' => 29],
          '3' => ['normal' => 99, 'discounted' => 79],
          '6' => ['normal' => 179, 'discounted' => 149],
          '12' => ['normal' => 299, 'discounted' => 259]
        ],
        'premium' => [
          '1' => ['normal' => 99, 'discounted' => 79],
          '3' => ['normal' => 249, 'discounted' => 219],
          '6' => ['normal' => 429, 'discounted' => 379],
          '12' => ['normal' => 799, 'discounted' => 739]
        ]
      ];

      $amount = $costs[$type][$term][$discountType] * 100;
      $description = ucfirst($type) . " plan for $term month(s)";

      // Charge the card.
      $err = StripeBilling::charge($customerId, $cardId, $amount, $description);
      if (!is_null($err)) {
        return self::ajaxError($err);
      }

      $message = "
        _id: $recruiterId<br />
        type: $type<br />
        term: $term<br />
        discount: $discount
      ";
      sendgmail(['tony.jiang@yale.edu', 'qingyang.chen@gmail.com'],
                "info@sublite.net",
                'Subscription Plan Bought!',
                $message);

      return self::ajaxSuccess();
    }

    public static function getCards() {
      RecruiterController::requireLogin();

      $recruiterId = $_SESSION['_id'];
      $cards = StripeBilling::getCardsByRecruiter($recruiterId);

      echo toJSON($cards);
    }
  }
?>