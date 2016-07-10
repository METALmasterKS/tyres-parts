<?php

namespace Tyres\Model;

class Price 
{

    public $id;
    public $tyreId;
    public $providerId;
    public $cityId;
    public $quantity;
    public $price;
    public $sale;
    
    private $discount;
    private $provider;
    private $cityName;

    const MARKUP = 20;

    public function __construct($discount) {
        $this->discount = $discount;
    }
    
    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id']))    ? (int) $data['id'] : null;
        $this->tyreId       = (isset($data['tyreId']))  ? (int) $data['tyreId'] : null;
        $this->providerId   = (isset($data['providerId']))  ? $data['providerId'] : null;
        $this->cityId       = (isset($data['cityId']))  ? $data['cityId'] : null;
        $this->quantity     = (isset($data['quantity']))  ? $data['quantity'] : null;
        $this->price        = (isset($data['price']))  ? $data['price'] : null;
        $this->sale         = (isset($data['sale']))  ? $data['sale'] : null;
        
        $this->cityName     = (isset($data['cityName'])) ? $data['cityName'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setDiscount($discount){
        $this->discount = $discount;
        return $this;
    }
    
    public function getDiscount(){
        return $this->discount;
    }
    
    public function getPrice() {
        return round(($this->price * (100 + self::MARKUP - $this->discount) / 100)) ;
    }

    public function setProvider(\Tyres\Model\Provider $provider) {
        $this->provider = $provider;
        return $this;
    }

    public function getProvider() {
        return $this->provider;
    }
    
    public function getCityName(){
        return $this->cityName;
    }
    
    public function setCityName($val){
        $this->cityName = $val;
        return $this;
    }
}