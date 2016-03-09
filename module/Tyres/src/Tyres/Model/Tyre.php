<?php

namespace Tyres\Model;

use Zend\Db\RowGateway\RowGatewayInterface;
use Zend\Db\RowGateway\RowGateway;

class Tyre 
{
    public $id;
    public $modelId;
    public $width;
    public $height;
    public $diameter;
    public $load;
    public $speed;
    public $spikes;
    public $XL;
    public $RFT;
    
    private $prices;
        
    public function __construct()
    {
        
    }
    
    public function exchangeArray($data)
    {
        $this->id       = isset($data['id'])        ? (int)     $data['id'] : null;
        $this->modelId  = isset($data['modelId'])   ? (int)     $data['modelId'] : null;
        $this->width    = isset($data['width'])     ? (int)     $data['width'] : null;
        $this->height   = isset($data['height'])    ? (int)     $data['height'] : null;
        $this->diameter = isset($data['diameter'])  ? (string)  $data['diameter'] : null;
        $this->load     = isset($data['load'])      ? (string)  $data['load'] : null;
        $this->speed    = isset($data['speed'])     ? (string)  $data['speed'] : null;
        $this->spikes   = isset($data['spikes'])    ? (int)    $data['spikes'] : 0;
        $this->XL       = isset($data['XL'])        ? (int)    $data['XL'] : 0;
        $this->RFT      = isset($data['RFT'])       ? (int)    $data['RFT'] : 0;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    
    public function setPrices(\Tyres\Model\Price $price) {
        $this->prices = $price;
    }
    
    public function getPrices() {
        return $this->prices ?: array();
    }
    
    public function addPrice($price) {
        $this->prices[] = $price;
        return $this;
    }
}
