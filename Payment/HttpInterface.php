<?php

namespace App\Payment;

interface HttpInterface
{
    public function post(string $url, array $payload): array;
}