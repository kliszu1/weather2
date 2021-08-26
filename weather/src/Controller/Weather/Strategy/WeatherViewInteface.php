<?php

namespace App\Controller\Weather\Strategy;

/**
 * Description of WeatherViewInteface
 *
 * @author Kliszu
 */
interface WeatherViewInteface {
    public function setWeatherData(array $data);
    public function init();
    public function prepareView();
}
