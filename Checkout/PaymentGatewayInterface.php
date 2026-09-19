<?php

namespace App\Checkout;

interface PaymentGatewayInterface
{
    /**
     * تنفيذ عملية الخصم/الدفع لمبلغ معين
     *
     * @param float $amount المبلغ المراد دفعه
     * @return bool إرجاع true إذا تمت عملية الدفع بنجاح، و false في حال الفشل
     */
    public function charge(float $amount): bool;
}
