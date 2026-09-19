<?php

namespace App\Audit;

use Throwable;

class AuditLogService
{
    private LoggerInterface $logger;
    private array $logs = [];

    /**
     * حقن الـ Logger عبر Dependency Injection لتسهيل استبداله بـ Mock Object في الاختبارات
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * تسجيل حركة/نشاط للمستخدم في النظام
     *
     * @param int $userId معرف المستخدم
     * @param string $action الإجراء الذي قام به (مثل: login, view_dashboard, update_profile)
     * @param array $details تفاصيل وسياق إضافي عن الحركة
     * @return bool ترجع true إذا تم التسجيل بنجاح، و false في حال حدوث خطأ
     */
    public function logActivity(int $userId, string $action, array $details = []): bool
    {
        // 1. التحقق من صحة معرف المستخدم
        if ($userId <= 0) {
            $this->logger->error('فشل تسجيل الحركة: معرف المستخدم غير صالح.', [
                'user_id' => $userId,
                'action' => $action,
            ]);
            return false;
        }

        // 2. التحقق من تحديد الإجراء المتخذ
        if (trim($action) === '') {
            $this->logger->error('فشل تسجيل الحركة: يجب تحديد الإجراء المتخذ.', [
                'user_id' => $userId,
            ]);
            return false;
        }

        try {
            // محاكاة حفظ حركة المستخدم بنجاح
            $this->logs[] = [
                'user_id' => $userId,
                'action' => $action,
                'details' => $details,
                'timestamp' => time(),
            ];

            return true;
        } catch (Throwable $e) {
            // 3. استدعاء اللوجر عند حدوث أي خطأ أو استثناء غير متوقع أثناء المعالجة
            $this->logger->error('حدث استثناء أثناء حفظ سجل حركة المستخدم: ' . $e->getMessage(), [
                'user_id' => $userId,
                'action' => $action,
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * استرجاع السجلات المسجلة
     *
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }
}
