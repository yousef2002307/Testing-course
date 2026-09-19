<?php

namespace App\Notification;

interface MailerInterface
{
    /**
     * Send an email message
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject line
     * @param string $body Email content body
     * @return void
     */
    public function send(string $to, string $subject, string $body): void;
}
