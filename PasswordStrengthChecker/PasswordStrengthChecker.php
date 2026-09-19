<?php

namespace App\PasswordStrengthChecker;

class PasswordStrengthChecker
{
    public const STRENGTH_WEAK = 'Weak';
    public const STRENGTH_MEDIUM = 'Medium';
    public const STRENGTH_STRONG = 'Strong';

    /**
     * فحص قوة كلمة المرور وإرجاع النتيجة (Weak, Medium, Strong)
     *
     * @param string $password
     * @return string
     */
    public function check(string $password): string
    {
        $length = mb_strlen($password);

        // إذا كانت كلمة المرور أقل من 8 أحرف، فهي ضعيفة دائماً
        if ($length < 8) {
            return self::STRENGTH_WEAK;
        }

        $hasLowercase = preg_match('/[a-z]/', $password) === 1;
        $hasUppercase = preg_match('/[A-Z]/', $password) === 1;
        $hasNumbers   = preg_match('/[0-9]/', $password) === 1;
        $hasSymbols   = preg_match('/[^a-zA-Z0-9]/', $password) === 1;

        // حساب عدد الشروط المحققة من حيث تنوع الرموز
        $passedCriteria = (int)$hasLowercase + (int)$hasUppercase + (int)$hasNumbers + (int)$hasSymbols;

        // الحالة القوية (Strong):
        // الطول 10 أحرف أو أكثر + تحقيق على الأقل 3 شروط من ضمنها رمز خاص
        if ($length >= 10 && $passedCriteria >= 3 && $hasSymbols) {
            return self::STRENGTH_STRONG;
        }

        // الحالة المتوسطة (Medium):
        // الطول 8 أحرف على الأقل + توفر تنوع أساسي (شرطين أو أكثر مثل أحرف وأرقام أو رموز)
        if ($length >= 8 && $passedCriteria >= 2) {
            return self::STRENGTH_MEDIUM;
        }

        // إذا لم تحقق الشروط السابقة (مثلاً: 8 أحرف كلها أرقام فقط أو أحرف صغيرة فقط)
        return self::STRENGTH_WEAK;
    }
}
