<?php
  interface Billing {
    /**
     * @return A customer id.
     */
    public static function createCustomer($email);

    public static function addCard($customerId, $token);
    public static function removeCard($customerId, $cardId);

    public static function getCardIds($customerId);

    /**
     * @return True on success, false on failure.
     */
    public static function charge($customerId, $cardId, $amount);
  }

  class StripeBilling implements Billing {
    const CURRENCY = 'usd';

    public static function createCustomer($email) {
      $customer = \Stripe\Customer::create([
        'email' => $email
      ]);
      return $customer->id;
    }

    public static function addCard($customerId, $token) {
      $customer = \Stripe\Customer::retrieve($customerId);
      $customer->sources->create([
        'source' => $token
      ]);
    }

    public static function removeCard($customerId, $cardId) {
      $customer = \Stripe\Customer::retrieve($customerId);
      $customer->sources->retrieve($cardId)->delete();
    }

    public static function getCardIds($customerId) {
      return \Stripe\Customer::retrieve($customerId)->sources->all([
        'object' => 'card'
      ]);
    }

    public static function charge($customerId, $cardId, $amount) {
      try {
        $charge = \Stripe\Charge::create([
          'customer' => $customerId,
          'source' => $cardId,
          'amount' => $amount,
          'currency' => self::CURRENCY
        ]);
        return true;
      } catch (\Stripe\Error\Card $e) {
        // The User's card has been declined.
        return false;
      }
    }
  }
?>