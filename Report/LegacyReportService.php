<?php

namespace App\Report;

class LegacyReportService
{
    private const VIP_DISCOUNT_RATE = 0.15;
    private const SILVER_DISCOUNT_RATE = 0.05;
    private const VIP_BULK_BONUS = 20.0;
    private const VIP_BULK_THRESHOLD = 5;

    private const COUPON_SAVE10_RATE = 0.10;
    private const COUPON_FLAT50_AMOUNT = 50.0;
    private const COUPON_FLAT50_MIN_SUBTOTAL = 200.0;

    private const TAX_RATE_EG = 0.14;
    private const TAX_RATE_SA = 0.15;
    private const TAX_RATE_DEFAULT = 0.05;

    private const FREE_SHIPPING_MIN_SUBTOTAL = 500.0;
    private const SMALL_ORDER_THRESHOLD = 100.0;
    private const SHIPPING_FEE_SMALL_ORDER = 25.0;
    private const SHIPPING_FEE_STANDARD = 10.0;

    private const LOYALTY_DOUBLE_POINTS_YEAR = 2020;

    /**
     * معالجة تقرير بيانات المستخدم والطلبات وحساب القيم المالية
     */
    public function processData(array $userData, array $items, string $coupon = ''): array
    {
        if ($this->isUserInactive($userData)) {
            return $this->createEmptyReport('INACTIVE_USER');
        }

        if (empty($items)) {
            return $this->createEmptyReport('EMPTY_ITEMS');
        }

        [$subtotal, $totalQuantity] = $this->calculateSubtotalAndQuantity($items);

        $tier = isset($userData['tier']) ? strtoupper($userData['tier']) : 'REGULAR';
        $discount = $this->calculateTotalDiscount($subtotal, $totalQuantity, $tier, $coupon);
        $afterDiscount = $subtotal - $discount;

        $country = isset($userData['country']) ? strtoupper($userData['country']) : 'DEFAULT';
        $isTaxExempt = !empty($userData['tax_exempt']);
        $tax = $this->calc_tax($afterDiscount, $country, $isTaxExempt);

        $shipping = $this->calculateShipping($subtotal, $afterDiscount, $tier);
        $grandTotal = round($afterDiscount + $tax + $shipping, 2);

        $memberYear = isset($userData['member_year']) ? (int)$userData['member_year'] : null;
        $points = $this->calculateLoyaltyPoints($afterDiscount, $memberYear);

        return [
            'status' => 'SUCCESS',
            'sub_total' => round($subtotal, 2),
            'disc_amt' => round($discount, 2),
            'tax' => round($tax, 2),
            'shipping' => round($shipping, 2),
            'grand_total' => $grandTotal,
            'points' => $points
        ];
    }

    /**
     * حساب الضريبة بناءً على الدولة والإعفاء الضريبي
     */
    public function calc_tax(float $amt, string $country, $is_exempt = false): float
    {
        if ($is_exempt) {
            return 0.0;
        }

        $rate = match ($country) {
            'EG' => self::TAX_RATE_EG,
            'SA' => self::TAX_RATE_SA,
            default => self::TAX_RATE_DEFAULT,
        };

        return round($amt * $rate, 2);
    }

    /**
     * إنشاء تقرير نصي
     */
    public function formatReportText(array $userData, array $items, string $coupon = ''): string
    {
        $report = $this->processData($userData, $items, $coupon);
        $customerName = strtoupper($userData['name'] ?? 'Customer');

        $lines = [
            "=== REPORT FOR: {$customerName} ===",
            "STATUS: {$report['status']}",
            "ITEMS COUNT: " . count($items),
            "SUBTOTAL: " . number_format($report['sub_total'], 2),
            "DISCOUNT: " . number_format($report['disc_amt'], 2),
            "TAX: " . number_format($report['tax'], 2),
            "SHIPPING: " . number_format($report['shipping'], 2),
            "TOTAL: " . number_format($report['grand_total'], 2),
            "POINTS: {$report['points']}",
            "================================="
        ];

        return implode("\n", $lines);
    }

    private function isUserInactive(array $userData): bool
    {
        if (!isset($userData['active'])) {
            return false;
        }

        return $userData['active'] === false || $userData['active'] === 0 || $userData['active'] === '0';
    }

    private function createEmptyReport(string $status): array
    {
        return [
            'status' => $status,
            'sub_total' => 0.0,
            'disc_amt' => 0.0,
            'tax' => 0.0,
            'shipping' => 0.0,
            'grand_total' => 0.0,
            'points' => 0
        ];
    }

    private function calculateSubtotalAndQuantity(array $items): array
    {
        $subtotal = 0.0;
        $totalQuantity = 0;

        foreach ($items as $item) {
            $price = isset($item['price']) ? (float)$item['price'] : 0.0;
            $quantity = isset($item['qty']) ? (int)$item['qty'] : 1;

            $subtotal += ($price * $quantity);
            $totalQuantity += $quantity;
        }

        return [$subtotal, $totalQuantity];
    }

    private function calculateTotalDiscount(float $subtotal, int $totalQuantity, string $tier, string $coupon): float
    {
        $discount = $this->calculateTierDiscount($subtotal, $totalQuantity, $tier);
        $discount += $this->calculateCouponDiscount($subtotal, $coupon);

        return min($discount, $subtotal);
    }

    private function calculateTierDiscount(float $subtotal, int $totalQuantity, string $tier): float
    {
        if ($tier === 'VIP') {
            $tierDiscount = $subtotal * self::VIP_DISCOUNT_RATE;
            if ($totalQuantity > self::VIP_BULK_THRESHOLD) {
                $tierDiscount += self::VIP_BULK_BONUS;
            }
            return $tierDiscount;
        }

        if ($tier === 'SILVER') {
            return $subtotal * self::SILVER_DISCOUNT_RATE;
        }

        return 0.0;
    }

    private function calculateCouponDiscount(float $subtotal, string $coupon): float
    {
        $normalizedCoupon = strtoupper(trim($coupon));

        if ($normalizedCoupon === 'SAVE10') {
            return $subtotal * self::COUPON_SAVE10_RATE;
        }

        if ($normalizedCoupon === 'FLAT50' && $subtotal >= self::COUPON_FLAT50_MIN_SUBTOTAL) {
            return self::COUPON_FLAT50_AMOUNT;
        }

        return 0.0;
    }

    private function calculateShipping(float $subtotal, float $afterDiscount, string $tier): float
    {
        if ($tier === 'VIP' || $subtotal >= self::FREE_SHIPPING_MIN_SUBTOTAL) {
            return 0.0;
        }

        if ($afterDiscount < self::SMALL_ORDER_THRESHOLD) {
            return self::SHIPPING_FEE_SMALL_ORDER;
        }

        return self::SHIPPING_FEE_STANDARD;
    }

    private function calculateLoyaltyPoints(float $afterDiscount, ?int $memberYear): int
    {
        $points = (int)floor($afterDiscount / 10);

        if ($memberYear !== null && $memberYear < self::LOYALTY_DOUBLE_POINTS_YEAR) {
            $points *= 2;
        }

        return $points;
    }
}
