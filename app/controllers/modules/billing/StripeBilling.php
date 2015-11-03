<?php
  interface Billing {
    /**
     * @return A customer id.
     */
    public static function createCustomer($email);

    public static function addCard($customerId, $token);
    public static function removeCard($customerId, $cardId);

    /**
     * @return Array of cards with cardId, last4, expMonth, expYear.
     */
    public static function getCards($customerId);

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
      $card = $customer->sources->create([
        'source' => $token
      ]);
      return [
        'cardId' => $card->id,
        'last4' => $card->last4,
        'expMonth' => $card->exp_month,
        'expYear' => $card->exp_year
      ];
    }

    public static function removeCard($customerId, $cardId) {
      $customer = \Stripe\Customer::retrieve($customerId);
      $customer->sources->retrieve($cardId)->delete();
    }

    public static function getCards($customerId) {
      $customer = \Stripe\Customer::retrieve($customerId);
      $cardsCollection = \Stripe\Customer::retrieve($customerId)->sources->all([
        'object' => 'card'
      ]);

      $cards = [];
      foreach ($cardsCollection['data'] as $card) {
        $cards[] = [
          'cardId' => $card->id,
          'last4' => $card->last4,
          'expMonth' => $card->exp_month,
          'expYear' => $card->exp_year
        ];
      }
      return $cards;
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