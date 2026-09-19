<?php

use PHPUnit\Framework\TestCase;
use App\Notification\MailerInterface;
use App\Notification\PasswordResetService;
class PasswordTest extends TestCase{
    public function test_reset_password(){
        //arrange
        $token = uniqid();
        $email = "jo@jo.com";
           $resetUrl = "https://example.com/reset-password?token=" . urlencode($token);
        $subject = 'Reset Your Password';
        $body = "Hello, you can reset your password using the following link: {$resetUrl}";
        $mailinterface = $this->createMock(MailerInterface::class);
        $mailinterface->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($email),
                $this->equalTo($subject),
                $this->equalTo($body)
            );
        
        //act
        $passreset =new  PasswordResetService( $mailinterface);
       $val = $passreset->sendResetLink($email, $token);


        //assert
        $this->assertSame(true,$val);
    }
}