<?php

namespace App\BankAccount;

use Exception;
use InvalidArgumentException;

class BankAccount
{
    private float $balance;

    /**
     * تهيئة الحساب برصيد ابتدائي (القيمة الافتراضية 0)
     *
     * @param float $initialBalance
     * @throws InvalidArgumentException
     */
    public function __construct(float $initialBalance = 0.0)
    {
        if ($initialBalance < 0) {
            throw new InvalidArgumentException("can not open negative account");
        }

        $this->balance = $initialBalance;
    }

    /**
     * إيداع مبلغ في الحساب
     *
     * @param float $amount
     * @return void
     * @throws InvalidArgumentException
     */
    public function deposit(float $amount)
    {
        // try{
        if ($amount <= 0) {
            throw new InvalidArgumentException("can not open negative account");
        }

        
        $this->balance += $amount;
    // }catch(Exception $e){
    //     return null;

    // }
    }

    /**
     * سحب مبلغ من الحساب
     *
     * @param float $amount
     * @return void
     * @throws InvalidArgumentException
     */
    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("مبلغ السحب يجب أن يكون أكبر من الصفر. القيمة المدخلة: {$amount}");
        }

        if ($amount > $this->balance) {
            throw new InvalidArgumentException(
                "رصيدك الحالي ({$this->balance}) غير كافٍ لسحب المبلغ المطلوب ({$amount})."
            );
        }

        $this->balance -= $amount;
    }

    /**
     * استرجاع الرصيد الحالي
     *
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
