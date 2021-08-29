<?php

namespace App\Controller\Weather\Strategy;

use App\ValueObject\ApiResponse;

/**
 * Description of WeatherServicesInteface
 *
 * @author Kliszu
 */

interface WeatherServicesInteface {
    public function setCity(string $city);
    public function init() : ApiResponse;
    public function prepareDataToSend();
    public function getWeatherInfo(ApiResponse $apiResponse) : ApiResponse;
}
