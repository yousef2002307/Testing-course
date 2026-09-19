<?php

namespace StringCalculator;

class StringCalculator
{
    public function add(string $numbers): int
    {
       if(empty($numbers)){
           return 0;
       }else{
           return strlen($numbers);
       }
    }
}
