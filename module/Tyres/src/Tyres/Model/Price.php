<?php

namespace Tyres\Model;

class Price 
{

    public $id;
    public $tyreId;
    public $providerId;
    public $quantity;
    public $price;
    public $sale;
    
    private $provider;
    
    public function __construct() {
        
    }
    
    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id']))    ? (int) $data['id'] : null;
        $this->tyreId       = (isset($data['tyreId']))  ? (int) $data['tyreId'] : null;
        $this->providerId   = (isset($data['providerId']))  ? $data['providerId'] : null;
        $this->quantity     = (isset($data['quantity']))  ? $data['quantity'] : null;
        $this->price        = (isset($data['price']))  ? $data['price'] : null;
        $this->sale         = (isset($data['sale']))  ? $data['sale'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setProvider(\Tyres\Model\Provider $provider) {
        $this->provider = $provider;
    }
    
    public function getProvider() {
        return $this->provider;
    }
}