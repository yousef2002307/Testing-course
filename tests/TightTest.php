<?php

use PHPUnit\Framework\TestCase;
use App\Payment\PaymentService;
use App\Payment\HttpInterface;

class TightTest extends TestCase {
    
    public function test_it_works() {
        
      //arrange
      $amount = 100;
$cardNumber = '1234567890123456';
      $httpmock = $this->createMock(HttpInterface::class);
      $httpmock->expects($this->once())
      ->method('post')->willReturn(['status' => 'success']);


     
$paymentService = new PaymentService($httpmock);



      //act
$result = $paymentService->processPayment($amount, $cardNumber);


      //assert

      $this->assertTrue($result);
    }
}
    