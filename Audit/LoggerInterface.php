<?php

namespace App\Audit;

interface LoggerInterface
{
    /**
     * تسجيل رسالة خطأ (Error Log)
     *
     * @param string $message نص رسالة الخطأ
     * @param array $context بيانات وسياق إضافي عن الخطأ
     * @return void
     */
    public function error(string $message, array $context = []): void;

    /**
     * تسجيل رسالة معلوماتية (Info Log)
     *
     * @param string $message نص الرسالة
     * @param array $context بيانات وسياق إضافي
     * @return void
     */
    public function info(string $message, array $context = []): void;
}
