<?php

namespace App\User;

interface UserRepositoryInterface
{
    /**
     * التحقق من وجود البريد الإلكتروني مسبقاً
     */
    public function emailExists(string $email): bool;

    /**
     * حفظ مستخدم جديد
     */
    public function save(array $user): bool;

    /**
     * جلب مستخدم حسب المعرف
     */
    public function findById(int $id): ?array;
}
