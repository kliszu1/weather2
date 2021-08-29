<?php

namespace App\Controller\Weather\Strategy\WeatherServices;

use App\ValueObject\WeatherInformation;
use App\Controller\Weather\Strategy\WeatherServicesInteface;
use App\ValueObject\ApiResponse;
use App\Dictionary\ResponseApiStatusesDictionary;
use App\Dictionary\MessagesDictionary;

/**
 * Description of AccuWeatherService
 *
 * @author Kliszu
 */
class AccuWeatherService implements WeatherServicesInteface {
    
    const ACCU_WEATHER_API_KEY= 'LAGMNBSFNHjQ3n4ZsGz47mWEkGKem8hH';
    const ACCU_WEATHER_URL  = 'http://dataservice.accuweather.com/forecasts/v1/daily/1day/';
    const ACCU_LOCATIONS_URL  = 'http://dataservice.accuweather.com/locations/v1/cities/search?';
    
    /**
     * @var string 
     */
    private $city;
    
    /**
     * @var string 
     */
    private $cityKey;
    
    /**
     * @var array 
     */
    private $weatherConfiguration;
    
    /**
     * @var array 
     */
    private $cityConfiguration;
    
    public function setCity(string $city) :self {
        $this->city = $city;
        
        return $this;
    }

    public function init() : ApiResponse {
        $apiResponse = new ApiResponse();
        $apiResponse->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_OK);
        
        $this->prepareDataToSend();
        $apiResponse = $this->findCityKeyByName($apiResponse);
   
        if($apiResponse->getStatus() === ResponseApiStatusesDictionary::KEY_STATUS_OK) {
            $this->prepareWeatherDataToSend();
            $apiResponse = $this->getWeatherInfo($apiResponse);
        }
        
        return $apiResponse;
    }
 
    public function prepareDataToSend() {
        $cityUrl = self::ACCU_LOCATIONS_URL;
        $apiKey = self::ACCU_WEATHER_API_KEY;
        
        $query = [
            "apikey" => "apikey={$apiKey}",
            "city" => "q={$this->city}",
        ];
        
        $cityUrlData = implode('&', $query);
        
        $this->cityConfiguration = [
            CURLOPT_URL => $cityUrl.$cityUrlData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ];
    }
    
    public function prepareWeatherDataToSend() {
        $weatherUrl = self::ACCU_WEATHER_URL.$this->cityKey.'?';
        $apiKey = self::ACCU_WEATHER_API_KEY;
        
        $query = [
            "apikey" => "apikey={$apiKey}",
            "units" => "metric=true",
        ];
        
        $weathrtUrlData = implode('&', $query);
        
        $this->weatherConfiguration = [
            CURLOPT_URL => $weatherUrl.$weathrtUrlData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ];
    }


    public function findCityKeyByName(ApiResponse $apiResponse) : ApiResponse {
        $curl = curl_init();
        curl_setopt_array($curl, $this->cityConfiguration);
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
         
        if(empty($response)){
            return $apiResponse
                ->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_ERROR)
                ->setMessage(MessagesDictionary::STATUS_DATA_DOWNLOAD_ERROR_VALUE);
        }
        
        if(empty($response[0]['Key'])){
            return $apiResponse
                ->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_ERROR)
                ->setMessage(MessagesDictionary::STATUS_BAD_LOCATION_VALUE);
        } else {
            $this->cityKey = $response[0]['Key'];
            return $apiResponse;
        }
    }
    
    public function getWeatherInfo(ApiResponse $apiResponse) : ApiResponse {
        $curl = curl_init();
        curl_setopt_array($curl, $this->weatherConfiguration);
        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);
        
        if(empty($response)){
            return $apiResponse
                ->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_ERROR)
                ->setMessage(MessagesDictionary::STATUS_DATA_DOWNLOAD_ERROR_VALUE);
        }
        
        $weatherInformation = new WeatherInformation(
            'brak informacji', 
            $response['DailyForecasts'][0]['Temperature']['Maximum']['Value'], 
            $response['DailyForecasts'][0]['Temperature']['Minimum']['Value']
        );
        
        return $apiResponse->setData($weatherInformation);
    }
}
