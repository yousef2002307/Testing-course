<?php

namespace App\User;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * مصفوفة تخزين المستخدمين في الذاكرة
     */
    private array $users = [];

    /**
     * فحص هل الإيميل موجود داخل المصفوفة
     */
    public function emailExists(string $email): bool
    {
        foreach ($this->users as $user) {
            if (isset($user['email']) && strtolower($user['email']) === strtolower($email)) {
                return true;
            }
        }
        return false;
    }

    /**
     * إضافة المستخدم إلى المصفوفة فقط إذا لم يكن البريد موجوداً مسبقاً
     */
    public function save(array $user): bool
    {
       
        $this->users[] = $user;
        return true;
    }

    /**
     * جلب مستخدم حسب المعرف
     */
    public function findById(int $id): ?array
    {
        foreach ($this->users as $user) {
            if (isset($user['id']) && $user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    /**
     * جلب جميع المستخدمين
     */
    public function all(): array
    {
        return $this->users;
    }
}
