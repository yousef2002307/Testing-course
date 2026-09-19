<?php

use PHPUnit\Framework\TestCase;
use App\Audit\LoggerInterface;
use App\Audit\AuditLogService;

class AuditTest extends TestCase
{
    /**
     * اختبار الحالة الناجحة: تسجيل النشاط بنجاح
     * التأكد من أن اللوجر لم يُستدعَ نهائياً (never) لأن العملية تمت بدون أخطاء
     */
    public function test_log_activity_successfully_does_not_call_logger()
    {
        // Arrange
        // إنشاء Mock للـ Logger
        $mockLogger = $this->createMock(LoggerInterface::class);

        // نتوقع أن دالة error لن يتم استدعاؤها نهائياً
        $mockLogger->expects($this->never())
                   ->method('error');

        $auditService = new AuditLogService($mockLogger);

        // Act
        $result = $auditService->logActivity(1, 'login', ['ip' => '127.0.0.1']);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(1, $auditService->getLogs());
    }

    /**
     * اختبار فشل العملية عند تمرير معرف مستخدم غير صالح (<= 0)
     * التأكد من استدعاء اللوجر مرة واحدة (once) مع المعاملات الصحيحة
     */
    public function test_log_activity_with_invalid_user_id_calls_logger_error_once()
    {
        // Arrange
        $invalidUserId = 0;
        $action = 'update_profile';

        $mockLogger = $this->createMock(LoggerInterface::class);

        // نتوقع استدعاء دالة error مرة واحدة بالضبط وبالرسالة والبيانات المتوقعة
        $mockLogger->expects($this->once())
                   ->method('error')
                   ->with(
                       $this->equalTo('فشل تسجيل الحركة: معرف المستخدم غير صالح.'),
                       $this->equalTo([
                           'user_id' => $invalidUserId,
                           'action' => $action,
                       ])
                   );

        $auditService = new AuditLogService($mockLogger);

        // Act
        $result = $auditService->logActivity($invalidUserId, $action);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(0, $auditService->getLogs());
    }

    /**
     * اختبار فشل العملية عند تمرير إجراء فارغ
     * التأكد من استدعاء اللوجر مرة واحدة بالرسالة المحددة
     */
    public function test_log_activity_with_empty_action_calls_logger_error_once()
    {
        // Arrange
        $userId = 5;
        $emptyAction = '   ';

        $mockLogger = $this->createMock(LoggerInterface::class);

        $mockLogger->expects($this->once())
                   ->method('error')
                   ->with(
                       $this->equalTo('فشل تسجيل الحركة: يجب تحديد الإجراء المتخذ.'),
                       $this->equalTo([
                           'user_id' => $userId,
                       ])
                   );

        $auditService = new AuditLogService($mockLogger);

        // Act
        $result = $auditService->logActivity($userId, $emptyAction);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(0, $auditService->getLogs());
    }
}
