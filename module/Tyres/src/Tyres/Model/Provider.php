<?php

namespace Tyres\Model;

class Provider 
{

    public $id;
    public $name;
    public $files;

    public function __construct()
    {
    }
    
    public function exchangeArray($data)
    {
        $this->id    = (isset($data['id']))    ? (int) $data['id'] : null;
        $this->name  = (isset($data['name']))  ? $data['name'] : '';
        $this->files  = (isset($data['files']))  ? $data['files'] : '';
        
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}