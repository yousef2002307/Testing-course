<?php

namespace App\Weather;

class WeatherService
{
    private WeatherApiClientInterface $apiClient;

    // النطاق المثالي لدرجة الحرارة للخروج
    public const MIN_COMFORTABLE_TEMP = 18.0;
    public const MAX_COMFORTABLE_TEMP = 30.0;

    public function __construct(WeatherApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * التحقق مما إذا كان الجو مناسباً للخروج بناءً على درجة الحرارة
     *
     * @param string $city اسم المدينة المراد فحص طقسها
     * @return bool true إذا كانت درجة الحرارة بين 18 و 30 درجة مئوية، وإلا false
     */
    public function isSuitableForGoingOut(string $city): bool
    {
        $temperature = $this->apiClient->getTemperature($city);

        return $temperature >= self::MIN_COMFORTABLE_TEMP && $temperature <= self::MAX_COMFORTABLE_TEMP;
    }
}
