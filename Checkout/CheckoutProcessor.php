<?php

namespace App\Checkout;

use InvalidArgumentException;

class CheckoutProcessor
{
    private PaymentGatewayInterface $paymentGateway;

    /**
     * تمرير بوابة الدفع عبر الـ Dependency Injection لتسهيل عمل الـ Stubs في الاختبارات
     *
     * @param PaymentGatewayInterface $paymentGateway
     */
    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * إتمام عملية الشراء لطلب بمبلغ محدد
     *
     * @param float $amount المبلغ الإجمالي للطلب
     * @return array مصفوفة تحتوي على حالة العملية ورسالة توضيحية
     * @throws InvalidArgumentException إذا كان المبلغ غير صالح (أقل من أو يساوي صفر)
     */
    public function checkout(float $amount): array
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('يجب أن يكون مبلغ الشراء أكبر من الصفر.');
        }

        $paymentSuccess = $this->paymentGateway->charge($amount);

        if ($paymentSuccess) {
            return [
                'status' => 'success',
                'message' => "good",
            ];
        }

        return [
            'status' => 'failed',
            'message' => "bad"
        ];
    }
}
