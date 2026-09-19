<?php

namespace App\Payment;

class PaymentService
{
    public function __construct(private HttpInterface $httpClient)
    {

    }
    /**
     * معالجة الدفع - مثال على Tight Coupling وغير قابل للاختبار (Untestable)
     *
     * يتم إنشاء كائن CurlHttpClient بالداخل مباشرة عبر كلمة new
     * دون استخدام حقن التبعيات (Dependency Injection)، مما يجعل استبداله بـ Mock أو Stub أمراً مستحيلاً.
     *
     * @param float $amount المبلغ المراد دفعه
     * @param string $cardNumber رقم البطاقة
     * @return bool نجاح أو فشل العملية
     */
    public function processPayment(float $amount, string $cardNumber): bool
    {
     
    

        $response = $this->httpClient->post('https://api.paymentgateway.com/v1/charge', [
            'amount' => $amount,
            'card'   => $cardNumber,
        ]);

        if (isset($response['status']) && $response['status'] === 'success') {
            return true;
        }

        return false;
    }
}

