<?php

namespace App\ValueObject;

use App\ValueObject\WeatherInformation;
use JsonSerializable;

/**
 * Description of ApiResponse
 *
 * @author Kliszu
 */
class ApiResponse implements JsonSerializable{
    
    /**
     * @var string 
     */
    private $status;
    
    /**
     * @var string 
     */
    private $message;
    
    /**
     * @var string 
     */
    private $html;
    
    /**
     * @var WeatherInformation
     */
    private $data;

    public function getStatus() :string {
        return $this->status;
    }

    public function getMessage() :string {
        return $this->message;
    }

    public function getHtml() :string {
        return $this->html;
    }

    public function getData() : WeatherInformation {
        return $this->data;
    }

    public function setStatus(string $status) :self  {
        $this->status = $status;
        
        return $this;
    }

    public function setMessage(string $message) :self  {
        $this->message = $message;
        
        return $this;
    }

    public function setHtml(string $html) :self  {
        $this->html = $html;
        
        return $this;
    }

    public function setData(WeatherInformation $data) :self {
        $this->data = $data;
        
        return $this;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
