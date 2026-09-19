<?php

namespace App\Notification;

class PasswordResetService
{
    private MailerInterface $mailer;

    /**
     * Inject MailerInterface via Dependency Injection
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send password reset link to user email
     *
     * @param string $email User email address
     * @param string $token Password reset token
     * @return bool True on success, false on failure
     */
    public function sendResetLink(string $email, string $token): bool
    {
        // 1. Validate email address format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // 2. Validate token is not empty
        if (trim($token) === '') {
            return false;
        }

        $resetUrl = "https://example.com/reset-password?token=" . urlencode($token);
        $subject = 'Reset Your Password';
        $body = "Hello, you can reset your password using the following link: {$resetUrl}";

        // 3. Trigger mail service (side effect / interaction)
        $this->mailer->send($email, $subject, $body);

        return true;
    }

    /**
     * Notify user that their password was changed successfully
     *
     * @param string $email User email address
     * @return bool True on success, false on failure
     */
    public function notifyPasswordChanged(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $subject = 'Password Changed Successfully';
        $body = 'Your password has been changed. If you did not make this change, please contact support immediately.';

        $this->mailer->send($email, $subject, $body);

        return true;
    }
}
