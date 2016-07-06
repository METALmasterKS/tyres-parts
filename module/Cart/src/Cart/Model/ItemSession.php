<?php

namespace Cart\Model;

class ItemSession 
{
    public $id;
    public $cart_id;
    public $date_added;
    public $object_type;
    public $object_id;
    
    public $type;
    public $name;
    public $price;
    public $count;
    public $url;
    public $image;
    
    private $object;
    
    public function __sleep()
    {
        $this->object = null;
        return ['id', 'cart_id', 'date_added', 'object_type', 'object_id', 'type', 'name', 'price', 'count', 'url', 'image'];
    }

    public function setObject($obj) {
        $this->object = $obj;
    }
    
    public function getObject() {
        return $this->object;
    }
    
    public function getPrice() {
        if ($this->object)
            return $this->object->price;
        elseif ($this->price)
            return $this->price;
        else 
            return null;
    }
        
    public function getCount(){
        return $this->count;
    }
    
    public function getSumm(){
        return $this->getPrice()*$this->getCount();
    }
    
    public function isRelevant(){
        return (time() < $this->date_added + 24*60*60);
    }
    
    
}
