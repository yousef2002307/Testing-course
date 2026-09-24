<?php

declare(strict_types=1);

readonly class Customer
{
    public function __construct(
        public int $id,
        public int $loyaltyYears,
        public int $pastOrderCount,
        public bool $isVip
    ) {}

    public function qualifiesForLoyaltyDiscount(): bool
    {
        return $this->loyaltyYears >= 5 || $this->pastOrderCount > 50;
    }
}

readonly class Cart
{
    public function __construct(
        public float $totalAmount
    ) {}
}

readonly class DiscountResult
{
    public function __construct(
        public float $discountAmount,
        public float $effectiveRate,
        public float $finalTotal
    ) {}
}

/**
 * Deep Module:
 * Clean, compact signature. Adding new customer or discount attributes
 * does NOT break callers or force new arguments!
 */
class CustomerDiscountCalculator
{
    private const MAX_DISCOUNT_RATE = 0.40;

    public function __construct(
        private bool $isBlackFriday = false
    ) {}

    public function calculate(
        Cart $cart,
        Customer $customer,
        ?string $couponCode = null,
        ?float $customOverrideRate = null
    ): DiscountResult {
        if ($customOverrideRate !== null) {
            $effectiveRate = min(1.0, max(0.0, $customOverrideRate));
            $amount = $cart->totalAmount * $effectiveRate;

            return new DiscountResult(
                discountAmount: $amount,
                effectiveRate: $effectiveRate,
                finalTotal: $cart->totalAmount - $amount
            );
        }

        $rate = 0.0;

        if ($this->isBlackFriday) {
            $rate += 0.20;
        }

        if ($customer->isVip) {
            $rate += 0.10;
        } elseif ($customer->qualifiesForLoyaltyDiscount()) {
            $rate += 0.05;
        }

        if ($couponCode === 'SAVE15') {
            $rate += 0.15;
        }

        $cappedRate = min(self::MAX_DISCOUNT_RATE, $rate);
        $discountAmount = $cart->totalAmount * $cappedRate;

        return new DiscountResult(
            discountAmount: $discountAmount,
            effectiveRate: $cappedRate,
            finalTotal: $cart->totalAmount - $discountAmount
        );
    }
}
