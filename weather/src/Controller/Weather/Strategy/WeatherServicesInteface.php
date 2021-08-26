<?php

namespace App\Controller\Weather\Strategy;

/**
 * Description of WeatherServicesInteface
 *
 * @author Kliszu
 */
interface WeatherServicesInteface {
    public function setCity(string $city);
    public function init();
    public function prepareDataToSend();
    public function getWeatherInfo();
}
