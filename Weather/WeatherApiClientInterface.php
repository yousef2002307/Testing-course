<?php

namespace App\Weather;

interface WeatherApiClientInterface
{
    /**
     * جلب درجة الحرارة الحالية لمدينة معينة بالمئوية (°C)
     *
     * @param string $city اسم المدينة
     * @return float درجة الحرارة
     */
    public function getTemperature(string $city): float;
}
