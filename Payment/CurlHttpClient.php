<?php

namespace App\Payment;
class CurlHttpClient implements HttpInterface
{
    /**
     * إرسال طلب POST عبر cURL والاتصال مباشرة بالشبكة
     *
     * @param string $url
     * @param array $payload
     * @return array
     */
    public function post(string $url, array $payload): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?? [];
    }
}
