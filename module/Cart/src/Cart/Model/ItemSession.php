<?php

namespace Cart\Model;

class ItemSession 
{
    public $id;
    public $cart_id;
    public $object_type;
    public $object_id;
    public $count;
    
    private $object;
    
    public function __sleep()
    {
        $this->object = null;
        return ['id', 'cart_id', 'object_type', 'object_id', 'count'];
    }

    public function setObject($variant) {
        $this->object = $variant;
    }
    
    public function getObject() {
        return $this->object;
    }
    
    public function getPrice(){
        if ($this->object)
            return $this->object->price;
        else 
            return null;
    }
        
    public function getCount(){
        return $this->count;
    }
    
    public function getSumm(){
        return $this->getPrice()*$this->getCount();
    }
}
