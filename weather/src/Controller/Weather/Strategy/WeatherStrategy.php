<?php

namespace App\Controller\Weather\Strategy;

use App\Dictionary\WeatherServicesDictionary;
use App\Controller\Weather\Strategy\WeatherServices\OpenWeatherService;
use App\Controller\Weather\Strategy\WeatherServices\AccuWeatherService;
/**
 * Description of WeatherStrategy
 *
 * @author Kliszu
 */
class WeatherStrategy {
    
    public function selectService(string $service) : ?object
    {
        switch($service){
            case WeatherServicesDictionary::KEY_OPEN_WEATHER:
                return new OpenWeatherService();
            break;
            case WeatherServicesDictionary::KEY_ACCU_WEATHER:
                return new AccuWeatherService();
            break;
            default:
                return null;
        }
    }
}
