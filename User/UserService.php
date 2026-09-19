<?php

namespace App\User;

use InvalidArgumentException;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * إضافة مستخدم جديد بعد التأكد من عدم تكرار البريد الإلكتروني
     *
     * @param array $userData مصفوفة بيانات المستخدم يجب أن تحتوي على 'email'
     * @return bool
     * @throws InvalidArgumentException إذا كان البريد موجوداً مسبقاً
     */
    public function addUser(array $userData): bool
    {
        if (empty($userData['email'])) {
            throw new InvalidArgumentException("Email is required.");
        }

        // التحقق من وجود الإيميل عبر الإنترفيس
        if ($this->userRepository->emailExists($userData['email'])) {
            throw new InvalidArgumentException("Email already exists: " . $userData['email']);
        }

        // الحفظ إذا لم يكن موجوداً
        return $this->userRepository->save($userData);
    }

    /**
     * جلب بيانات مستخدم
     */
    public function getUser(int $id): ?array
    {
        return $this->userRepository->findById($id);
    }
}
