<?php

namespace App\Controller\Weather\Strategy\WeatherServices;

use App\ValueObject\WeatherInformation;
use App\Controller\Weather\Strategy\WeatherServicesInteface;
use App\ValueObject\ApiResponse;
use App\Dictionary\ResponseApiStatusesDictionary;
use App\Dictionary\MessagesDictionary;

/**
 * Description of YahooServices
 *
 * @author Kliszu
 */
class OpenWeatherService implements WeatherServicesInteface {
    
    const OPEN_WEATHER_API_KEY= '5337f0b253eef61d12e9d7102e8fe245';
    const OPEN_WEATHER_URL  = 'https://api.openweathermap.org/data/2.5/weather?';
    
    /**
     * @var string 
     */
    private $city;
    
    /**
     * @var array 
     */
    private $configuration;
    
    /**
     * @var ApiResponse 
     */
    private $apiResponse;
    
    public function __construct() {
       $this->apiResponse = new ApiResponse();
    }

    /**
     * @param string $city
     */
    public function setCity(string $city) :self {
        $this->city = $city;
        
        return $this;
    }

    public function init() : ApiResponse  {
        $apiResponse = new ApiResponse();
        $apiResponse->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_OK);
        
        $this->prepareDataToSend();
        return $this->getWeatherInfo($apiResponse);
    }
 
    public function prepareDataToSend() {
        $url = self::OPEN_WEATHER_URL;
        $apiKey = self::OPEN_WEATHER_API_KEY;
        
        $query = [
            "city" => "q={$this->city}",
            "appid" => "appid={$apiKey}",
            "units" => "units=metric",
        ];
        
        $urlData = implode('&', $query);
        
        $this->configuration = [
            CURLOPT_URL => $url.$urlData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ];
    }
    
    public function getWeatherInfo(ApiResponse $apiResponse) : ApiResponse {
        $curl = curl_init();
        curl_setopt_array($curl, $this->configuration);
        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);
        
        if(empty($response)){
            return $this->apiResponse
                ->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_ERROR)
                ->setMessage(MessagesDictionary::KEY_STATUS_DATA_DOWNLOAD_ERROR);
        }
        
        if(empty($response['coord'])){
            return $apiResponse
                ->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_ERROR)
                ->setMessage(MessagesDictionary::KEY_STATUS_BAR_LOCATION);
        }
        
        $weatherInformation = new WeatherInformation(
            $response['main']['temp'], 
            $response['main']['temp_max'], 
            $response['main']['temp_min']
        );
        
        return $apiResponse->setData($weatherInformation);
    }
}
