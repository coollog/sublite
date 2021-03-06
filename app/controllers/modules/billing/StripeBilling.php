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

    public static function getCardsByRecruiter(MongoId $recruiterId);

    /**
     * @return Null on success, error message on error.
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

    public static function getCardsByRecruiter(MongoId $recruiterId) {
      // Get cards.
      $customerId = RecruiterModel::getCustomerId($recruiterId);
      if (is_null($customerId)) {
        $cards = [];
      } else {
        $cards = StripeBilling::getCards($customerId);
      }

      return $cards;
    }

    public static function charge($customerId,
                                  $cardId,
                                  $amount,
                                  $description = null) {
      try {
        $charge = \Stripe\Charge::create([
          'customer' => $customerId,
          'source' => $cardId,
          'amount' => $amount,
          'currency' => self::CURRENCY,
          'description' => $description
        ]);
        return null;
      } catch (\Stripe\Error\ApiConnection $e) {
        return 'Network error, perhaps try again.';
      } catch (\Stripe\Error\InvalidRequest $e) {
        return 'Transaction invalid.';
      } catch (\Stripe\Error\Api $e) {
        return 'Transaction servers down. Try again later.';
      } catch (\Stripe\Error\Card $e) {
        $e_json = $e->getJsonBody();
        $error = $e_json['error'];
        return "Card was declined: $error";
      }
      return 'Transaction failure.';
    }
  }
?>