<?php
namespace App\ValueObject;

/**
 * Description of WeatherInformation
 *
 * @author Kliszu
 */
class WeatherInformation {
    
    /**
     * @var string 
     */
    private $currentTemperature;
    
    /**
     * @var string 
     */
    private $maxTemperature;
    
    /**
     * @var string 
     */
    private $minTemperature;
    
    
    public function __construct(string $currentTemperature, string $maxTemperature, string $minTemperature) {
        $this->currentTemperature = $currentTemperature;
        $this->maxTemperature = $maxTemperature;
        $this->minTemperature = $minTemperature;
    }
    
    public function getCurrentTemperature() {
        return $this->currentTemperature;
    }

    public function getMaxTemperature() {
        return $this->maxTemperature;
    }

    public function getMinTemperature() {
        return $this->minTemperature;
    }
}
