<?php

use PHPUnit\Framework\TestCase;
use StringCalculator\StringCalculator;
class StringTest extends TestCase{
    public function test_empty_string(){
        //arrange
        $string = "";
        $stringclass = new StringCalculator();
        //act
       $val = $stringclass->add($string);
        //assert
        $this->assertSame($val,0);
    }

    public function test_string_full(){
        //arrange
        $string = "masr";

         $stringclass = new StringCalculator();
        //act
       $val = $stringclass->add($string);
        //assert
        $this->assertSame($val,strlen($string));
    }
}