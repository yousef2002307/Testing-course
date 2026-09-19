<?php
namespace App\OrderCalculator;
use InvalidArgumentException;
class OrderCalculator
{
    /**
     * حساب إجمالي الطلب بعد تطبيق الخصم وإضافة مصاريف الشحن
     *
     * @param array $items مصفوفة المنتجات (كل عنصر يحتوي على 'price' و 'quantity')
     * @param float $discountPercentage نسبة الخصم المئوية (مثال: 10 تعني 10%)
     * @param float $shippingCost مصاريف الشحن الثابتة
     * @return float إجمالي المبلغ النهائي
     * @throws InvalidArgumentException في حال وجود قيم غير صالحة
     */
    public function calculateTotal(array $items, float $discountPercentage = 0.0, float $shippingCost = 0.0): float
    {
        // التحقق من صحة المدخلات
        if ($discountPercentage < 0 || $discountPercentage > 100) {
            throw new InvalidArgumentException("نسبة الخصم يجب أن تكون بين 0 و 100.");
        }

        if ($shippingCost < 0) {
            throw new InvalidArgumentException("مصاريف الشحن لا يمكن أن تكون سالبة.");
        }

        // 1. حساب المجموع الفرعي للمنتجات (Subtotal)
        $subtotal = 0.0;
        foreach ($items as $item) {
            $price = $item['price'] ?? 0.0;
            $quantity = $item['quantity'] ?? 1;

            if ($price < 0 || $quantity < 0) {
                throw new InvalidArgumentException("السعر والكمية يجب أن يكونا قيماً موجبة.");
            }

            $subtotal += ($price * $quantity);
        }

        // 2. حساب قيمة الخصم المئوي
        $discountAmount = ($subtotal * $discountPercentage) / 100;
        $totalAfterDiscount = $subtotal - $discountAmount;

        // 3. إضافة مصاريف الشحن والحصول على الإجمالي النهائي
        $finalTotal = $totalAfterDiscount + $shippingCost;

        return round($finalTotal, 2);
    }

    /**
     * دالة مساعدة لحساب المجموع الفرعي فقط بدون خصم أو شحن
     */
    public function calculateSubtotal(array $items): float
    {
        return $this->calculateTotal($items, 0.0, 0.0);
    }
}
