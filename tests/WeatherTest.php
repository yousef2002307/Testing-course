<?php

use PHPUnit\Framework\TestCase;
use App\Weather\WeatherService;
use App\Weather\WeatherApiClientInterface;

class WeatherTest extends TestCase{

    public function test_syuit_going_out(){
        //arrange
     $weatherstub = $this->createStub(WeatherApiClientInterface::class);
     $weatherstub->method('getTemperature')->willReturn(25.5);
     $weatherService = new WeatherService($weatherstub);

        //act
     $weatherstatus = $weatherService->isSuitableForGoingOut("alex");
        //assert
        $this->assertTrue($weatherstatus);
    }
    
    public function test_not_suit_going_out(){
        //arrange
     $weatherstub = $this->createStub(WeatherApiClientInterface::class);
     $weatherstub->method('getTemperature')->willReturn(35.5);
     $weatherService = new WeatherService($weatherstub);

        //act
     $weatherstatus = $weatherService->isSuitableForGoingOut("alex");
        //assert
        $this->assertFalse($weatherstatus);
    }
}